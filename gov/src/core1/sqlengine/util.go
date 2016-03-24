package sqlengine

import (
	"errors"
	"reflect"
	"strconv"
	"strings"
	"time"

	"fmt"
)

func getTypeName(obj interface{}) (typestr string) {
	typ := reflect.TypeOf(obj)
	typestr = typ.String()

	lastDotIndex := strings.LastIndex(typestr, ".")
	if lastDotIndex != -1 {
		typestr = typestr[lastDotIndex+1:]
	}

	return
}

func snakeCasedName(name string) string {
	newstr := make([]rune, 0)
	firstTime := true

	for _, chr := range name {
		if isUpper := 'A' <= chr && chr <= 'Z'; isUpper {
			if firstTime == true {
				firstTime = false
			} else {
				newstr = append(newstr, '_')
			}
			chr -= ('A' - 'a')
		}
		newstr = append(newstr, chr)
	}

	return string(newstr)
}

func titleCasedName(name string) string {
	newstr := make([]rune, 0)
	upNextChar := true

	for _, chr := range name {
		switch {
		case upNextChar:
			upNextChar = false
			chr -= ('a' - 'A')
		case chr == '_':
			upNextChar = true
			continue
		}

		newstr = append(newstr, chr)
	}

	return string(newstr)
}

func pluralizeString(str string) string {
	if strings.HasSuffix(str, "data") {
		return str
	}
	if strings.HasSuffix(str, "y") {
		str = str[:len(str)-1] + "ie"
	}
	return str + "s"
}

//整体扫描tag
func scanMapIntoStruct(obj interface{}, objMap map[string][]byte) error {
	dataStruct := reflect.Indirect(reflect.ValueOf(obj))
	if dataStruct.Kind() != reflect.Struct {
		return errors.New("expected a pointer to a struct")
	}

	dataStructType := dataStruct.Type()

	for i := 0; i < dataStructType.NumField(); i++ {
		field := dataStructType.Field(i)
		fieldv := dataStruct.Field(i)

		err := scanMapElement(fieldv, field, objMap)
		if err != nil {
			return err
		}
	}

	return nil
}

func getBeanType(sliceElementType reflect.Type) (reflect.Type, error) {
	if sliceElementType.Kind() == reflect.Ptr {
		if sliceElementType.Elem().Kind() == reflect.Struct {
			return sliceElementType.Elem(), nil
		}
	} else if sliceElementType.Kind() == reflect.Struct {
		return sliceElementType, nil
	}
	return nil, errors.New("not slice type")
}

func scanMapElement(fieldv reflect.Value, field reflect.StructField, objMap map[string][]byte) error {
	objFieldName := field.Name
	//转换小写!!!
	objFieldName = strings.ToLower(objFieldName)
	bb := field.Tag
	sqlTag := bb.Get("sql")

	if bb.Get("db") == "-" || sqlTag == "-" || reflect.ValueOf(bb).String() == "-" {
		return nil
	}
	sqlTags := strings.Split(sqlTag, ",")
	sqlFieldName := objFieldName
	if len(sqlTags[0]) > 0 {
		sqlFieldName = sqlTags[0]
	}
	inline := false
	//omitempty := false //TODO! 忽略的类
	// CHECK INLINE  合并多个表
	if len(sqlTags) > 1 {
		if stringArrayContains("inline", sqlTags[1:]) {
			inline = true
		}
	}
	if inline {
		if field.Type.Kind() == reflect.Struct && field.Type.String() != "time.Time" {
			for i := 0; i < field.Type.NumField(); i++ {
				err := scanMapElement(fieldv.Field(i), field.Type.Field(i), objMap)
				if err != nil {
					return err
				}
			}
		} else {
			return errors.New("A non struct type can't be inline.")
		}
	}
	// not inline do nothing
	data, ok := objMap[sqlFieldName]

	if !ok {
		return nil
	}

	var v interface{}

	switch field.Type.Kind() {

	case reflect.Slice:
		v = data
	case reflect.String:
		v = string(data)
	case reflect.Bool:
		v = string(data) == "1"
	case reflect.Int8:
		x, err := strconv.Atoi(string(data))
		if err != nil {
			return errors.New("arg " + sqlFieldName + " as int: " + err.Error())
		}
		v = int8(x)
	case reflect.Int, reflect.Int16, reflect.Int32:
		x, err := strconv.Atoi(string(data))
		if err != nil {
			return errors.New("arg " + sqlFieldName + " as int: " + err.Error())
		}
		v = x
	case reflect.Int64:
		x, err := strconv.ParseInt(string(data), 10, 64)
		if err != nil {
			return errors.New("arg " + sqlFieldName + " as int: " + err.Error())
		}
		v = x
	case reflect.Float32, reflect.Float64:
		x, err := strconv.ParseFloat(string(data), 64)
		if err != nil {
			return errors.New("arg " + sqlFieldName + " as float64: " + err.Error())
		}
		v = x
	case reflect.Uint8, reflect.Uint16, reflect.Uint32, reflect.Uint64:
		x, err := strconv.ParseUint(string(data), 10, 64)
		if err != nil {
			return errors.New("arg " + sqlFieldName + " as int: " + err.Error())
		}
		v = x
	//Supports Time type only (for now)
	case reflect.Struct:
		if fieldv.Type().String() != "time.Time" {
			return errors.New("unsupported struct type in Scan: " + fieldv.Type().String())
		}

		x, err := time.Parse("2006-01-02 15:04:05", string(data))
		if err != nil {
			x, err = time.Parse("2006-01-02 15:04:05", string(data))

			if err != nil {
				return errors.New("unsupported time format: " + string(data))
			}
		}

		v = x
	default:
		return errors.New("unsupported type in Scan: " + reflect.TypeOf(v).String())
	}
	//int8
	fieldv.Set(reflect.ValueOf(v))

	return nil
}

//
func scanStructIntoMap(obj interface{}) (map[string]interface{}, error) {
	dataStruct := reflect.Indirect(reflect.ValueOf(obj))
	if dataStruct.Kind() != reflect.Struct {
		return nil, errors.New("expected a pointer to a struct")
	}

	dataStructType := dataStruct.Type()

	mapped := make(map[string]interface{})

	for i := 0; i < dataStructType.NumField(); i++ {
		field := dataStructType.Field(i)
		fieldv := dataStruct.Field(i)
		fieldName := field.Name
		bb := field.Tag
		sqlTag := bb.Get("sql")
		sqlTags := strings.Split(sqlTag, ",")
		var mapKey string

		inline := false

		if bb.Get("db") == "-" || sqlTag == "-" || reflect.ValueOf(bb).String() == "-" {
			continue
		} else if len(sqlTag) > 0 {
			//TODO: support tags that are common in json like omitempty
			if sqlTags[0] == "-" {
				continue
			}
			mapKey = sqlTags[0]
		} else {
			//小写
			mapKey = strings.ToLower(fieldName)
		}

		if len(sqlTags) > 1 {
			if stringArrayContains("inline", sqlTags[1:]) {
				inline = true
			}
		}

		if inline {
			// get an inner map and then put it inside the outer map
			map2, err2 := scanStructIntoMap(fieldv.Interface())
			if err2 != nil {
				return mapped, err2
			}
			for k, v := range map2 {
				mapped[k] = v
			}
		} else {
			value := dataStruct.FieldByName(fieldName).Interface()
			mapped[mapKey] = value
		}
	}

	return mapped, nil
}

//类名
func StructName(s interface{}) string {
	v := reflect.TypeOf(s)
	for v.Kind() == reflect.Ptr {
		v = v.Elem()
	}
	return v.Name()
}

//根据类名获得表名
func getTableName(s interface{}) string {
	v := reflect.TypeOf(s)

	if v.Kind() == reflect.String {
		s2, _ := s.(string)
		return snakeCasedName(s2)
	}
	tn := scanTableName(s)

	if len(tn) > 0 {
		return tn
	}
	return getTableName(StructName(s))
}

func scanTableName(s interface{}) string {
	if reflect.TypeOf(reflect.Indirect(reflect.ValueOf(s)).Interface()).Kind() == reflect.Slice {
		sliceValue := reflect.Indirect(reflect.ValueOf(s))
		//sliceElementType := sliceValue.Type().Elem()
		tt := sliceValue.Type().Elem()
		if tt.Kind() == reflect.Ptr {
			pv := reflect.New(tt.Elem())
			sliceElementType := pv.Elem().Type()
			for i := 0; i < sliceElementType.NumField(); i++ {
				bb := sliceElementType.Field(i).Tag
				if len(bb.Get("tname")) > 0 {
					return bb.Get("tname")
				}
			}

		} else if tt.Kind() == reflect.Struct {
			for i := 0; i < tt.NumField(); i++ {
				bb := tt.Field(i).Tag
				if len(bb.Get("tname")) > 0 {
					return bb.Get("tname")
				}
			}
		}

	} else {
		tt := reflect.TypeOf(reflect.Indirect(reflect.ValueOf(s)).Interface())
		for i := 0; i < tt.NumField(); i++ {
			bb := tt.Field(i).Tag
			if len(bb.Get("tname")) > 0 {
				return bb.Get("tname")
			}
		}
	}
	return ""

}

//获取pk
func getPKFromTag(tag reflect.StructTag, fieldname string) string {
	if tag.Get("db") == "PK" || reflect.ValueOf(tag).String() == "PK" {
		sqlTag := tag.Get("sql")
		sqlTags := strings.Split(sqlTag, ",")
		if len(sqlTag) > 0 {
			return sqlTags[0]
		} else {
			return fieldname
		}
	}
	return ""
}

//插入数据库的返回的主键id值设置
func SetPKValue(output interface{}, id int64) error {
	var field string
	tt := reflect.TypeOf(reflect.Indirect(reflect.ValueOf(output)).Interface())
	for i := 0; i < tt.NumField(); i++ {
		bb := tt.Field(i).Tag
		//有sql tag
		field = getPKFromTag(bb, strings.ToLower(tt.Field(i).Name))
		if len(field) > 0 {
			field = tt.Field(i).Name
			break
		}
	}
	if len(field) == 0 {
		return fmt.Errorf("Unable to save because primary key %q was not found in struct", field)
	}
	structPtr := reflect.ValueOf(output)
	structVal := structPtr.Elem()
	structField := structVal.FieldByName(field)
	//

	structField.Set(reflect.ValueOf(id))
	return nil
}

func stringArrayContains(needle string, haystack []string) bool {
	for _, v := range haystack {
		if needle == v {
			return true
		}
	}
	return false
}
