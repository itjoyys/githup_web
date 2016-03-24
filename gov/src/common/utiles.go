package common

import (
	"bytes"
	"compress/zlib"
	"crypto/md5"
	"encoding/base64"
	"encoding/hex"
	"errors"
	"fmt"
	"io"
	"io/ioutil"
	"math/rand"
	"net/http"
	"os"
	"strconv"
	"strings"
	"text/template"
	"time"

	"utility"
)

const (
	base64Table = "123QRSTUabcdVWXYZHijKLAWDCABDstEFGuvwxyzGHIJklmnopqr234560178912"
)

func Base64Encode(src []byte) []byte {
	coder := base64.NewEncoding(base64Table)
	return []byte(coder.EncodeToString(src))
}

func Base64Decode(src []byte) ([]byte, error) {
	coder := base64.NewEncoding(base64Table)
	return coder.DecodeString(string(src))
}

func Zlib(src []byte) []byte {
	var buf bytes.Buffer
	w := zlib.NewWriter(&buf)
	w.Write(src)
	w.Close()
	return buf.Bytes()
}

func UnZlib(dict []byte) ([]byte, error) {
	in := bytes.NewBuffer(dict)
	r, err := zlib.NewReader(in)
	if err != nil {
		return []byte{}, err
	}
	output, err := ioutil.ReadAll(r)
	if err != nil {
		return []byte{}, err
	}
	defer r.Close()
	return output, nil
}

//防值sql注入,和xss注入
func FilterInvade(str string) string {
	return template.HTMLEscapeString(str)
}

func PanicIf(err error) {
	if err != nil {
		panic(err)
	}
}

//抛出 验证中自定义的的错误
func PanicErrMsg(errmsg *ErrorMsg) {
	if errmsg.ErrorId > 0 {
		panic(errors.New(errmsg.Msg))
	}
}

func ParseInt(value string) int {
	if value == "" {
		return 0
	}
	val, _ := strconv.Atoi(value)
	return val
}

func IntString(value int) string {
	return strconv.Itoa(value)
}

func Md5(str string) string {
	h := md5.New()
	h.Write([]byte(str))
	return hex.EncodeToString(h.Sum(nil))
}

func CreateImagePath(name string, uid int64) (string, string) {
	fix_arr := strings.Split(name, ".")
	fix := "." + fix_arr[len(fix_arr)-1]
	path := time.Now().Format("2006/01/02")
	if uid == 0 {
		name = utility.ToStr(uid) + "_" + utility.ToStr(time.Now().Unix()) + fix
	} else {
		name = utility.ToStr(time.Now().UnixNano()) + fix
	}

	//time.Now().UnixNano(),
	//time.Now().Unix(), time.Now().Format("2006/01/02/150405")
	//time.Now().YearDay()
	//filename := strconv.FormatInt(userinfo.Uid, 10) + "_" + strconv.FormatInt(time.Now().Unix(), 10) + fix
	return path, name
}

func Atoa(str string) string {
	var result string
	for i := 0; i < len(str); i++ {
		c := rune(str[i])
		if 'A' <= c && c <= 'Z' && i > 0 {
			result = result + "_" + strings.ToLower(string(str[i]))
		} else {
			result = result + string(str[i])
		}
	}
	return result
}

func GetRemoteIp(r *http.Request) (ip string) {
	ip = r.Header.Get("X-Real-Ip")
	if ip == "" {
		ip = r.RemoteAddr
	}
	ip = strings.Split(ip, ":")[0]
	if len(ip) < 7 || ip == "127.0.0.1" {
		ip = "localhost"
	}
	return
}

/* Test Helpers
func Expect(t *testing.T, a interface{}, b interface{}) {
	if a != b {
		t.Errorf("Expected %v (type %v) - Got %v (type %v)", b, reflect.TypeOf(b), a, reflect.TypeOf(a))
	}
}
*/
// 按字节截取字符串 utf-8不乱码
func SubstrByByte(str string, length int) string {
	bs := []byte(str)[:length]
	bl := 0
	for i := len(bs) - 1; i >= 0; i-- {
		switch {
		case bs[i] >= 0 && bs[i] <= 127:
			return string(bs[:i+1])
		case bs[i] >= 128 && bs[i] <= 191:
			bl++
		case bs[i] >= 192 && bs[i] <= 253:
			cl := 0
			switch {
			case bs[i]&252 == 252:
				cl = 6
			case bs[i]&248 == 248:
				cl = 5
			case bs[i]&240 == 240:
				cl = 4
			case bs[i]&224 == 224:
				cl = 3
			default:
				cl = 2
			}
			if bl+1 == cl {
				return string(bs[:i+cl])
			}
			return string(bs[:i])
		}
	}
	return ""
}

func SubString(str string, begin, length int) (substr string) {
	// 将字符串的转换成[]rune
	rs := []rune(str)
	lth := len(rs)
	endstr := ""
	// 简单的越界判断
	if begin < 0 {
		begin = 0
	}
	if begin >= lth {
		begin = lth
	}
	end := begin + length
	if end > lth {
		end = lth
	} else {
		endstr = "..."
	}
	// 返回子串
	return string(rs[begin:end]) + endstr
}

func SnakeCasedName(name string) string {
	newstr := make([]rune, 0)
	name = strings.Replace(name, "ID", "Id", -1)
	for idx, chr := range name {
		if isUpper := 'A' <= chr && chr <= 'Z'; isUpper {
			if idx > 0 {
				newstr = append(newstr, '_')
			}
			chr -= ('A' - 'a')
		}
		newstr = append(newstr, chr)
	}

	return string(newstr)
}

func GenToken() string {
	nano := time.Now().UnixNano()
	rand.Seed(nano)
	rndNum := rand.Int63()
	uuid := Md5(Md5(strconv.FormatInt(nano, 10)) + Md5(strconv.FormatInt(rndNum, 10)))
	return uuid
}

/*	$uid1=$_POST["uid1"];
	$uid2=intval($uid1);
	$uid1 = abs($uid2);
	$uid1 = sprintf("%09d", $uid1);
	$dir1 = substr($uid1, 0, 3);
	$dir2 = substr($uid1, 3, 2);
	$dir3 = substr($uid1, 5, 2);*/
//utype [0,1] 0保存路径  1url路径
func GetAvatar(uid int64, utype int, atype string) string {
	str_uid := fmt.Sprintf("%09d", uid)
	dir1 := str_uid[0:3]
	dir2 := str_uid[3:5]
	dir3 := str_uid[5:7]
	fix := str_uid[7:9]
	path := AvatarUploadPath + "/" + dir1 + "/" + dir2 + "/" + dir3
	err := os.MkdirAll(path, 0777)
	if err != nil {
		Log.Err(err)
		return ""
	}
	if utype > 0 {
		if !isExists(AvatarUploadPath + "/" + dir1 + "/" + dir2 + "/" + dir3 + "/" + fix + "_" + atype + ".jpg") {
			return ""
		}
		return AvatarUrl + "/" + dir1 + "/" + dir2 + "/" + dir3 + "/" + fix + "_" + atype + ".jpg"
	} else {
		return AvatarUploadPath + "/" + dir1 + "/" + dir2 + "/" + dir3 + "/" + fix + "_" + atype + ".jpg"
	}

}

func isExists(path string) bool {
	_, err := os.Stat(path)
	if err == nil {
		return true
	}
	return os.IsExist(err)
}

//获取文件修改时间
func FileMTime(file string) (int64, error) {
	f, e := os.Stat(file)
	if e != nil {
		return 0, e
	}
	return f.ModTime().Unix(), nil
}

//atype [big middle small]
func SaveRemoteAvatar(url string, uid int64, atype string) error {

	//新建文件 头像文件avatar
	apath := GetAvatar(uid, 0, atype)
	out, err := os.Create(apath)
	if err != nil {
		Log.Err(err)
		return err
	}
	defer out.Close()
	resp, err := http.Get(url)
	if err != nil {
		Log.Err(err)
		return err
	}
	defer resp.Body.Close()
	pix, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		Log.Err(err)
		return err
	}
	_, err = io.Copy(out, bytes.NewReader(pix))
	if err != nil {
		Log.Err(err)
	}
	return err
}

func CombineURL(baseURL string, keysArr map[string]string) string {
	combined := baseURL + "?"
	var valueArr []string

	for k, v := range keysArr {
		temp := k + "=" + v
		valueArr = append(valueArr, temp)
	}

	combined = combined + strings.Join(valueArr, "&")

	return combined
}

func Changetimezone(timestr, oldzone, newzone string) (string, error) {
	_, err := time.Parse("2006-01-02 15:04:05", timestr)
	if err != nil {
		return "", err
	}
	oldzone_loc, err := time.LoadLocation(oldzone)
	if err != nil {
		return "", err
	}
	newzone_loc, err := time.LoadLocation(newzone)
	if err != nil {
		return "", err
	}
	time_zone, err := time.ParseInLocation("2006-01-02 15:04:05", timestr, oldzone_loc)
	if err != nil {
		return "", err
	}
	return time_zone.In(newzone_loc).Format("2006-01-02 15:04:05"),nil
}