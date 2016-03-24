//文件日志
package common

import (
	mysql "github.com/go-sql-driver/mysql"
	"log"
	"reflect"

	"core/filelogger"
)

var Log *Logger

func InitLog() {
	Log = NewLog(LogRootPath)
}

type Logger struct {
	accesslog *filelogger.FileLogger
	//errorlog *filelogger.FileLogger
}

func NewLog(fileDir string) *Logger {
	loger := Logger{}
	loger.accesslog = filelogger.NewDailyLogger(fileDir, "access.log", "[INFO]", 300, 5000)

	//loger.errorlog = filelogger.NewDefaultLogger(fileDir, "error.log")
	//loger.errorlog.SetLogLevel(filelogger.WARN)

	mysql.SetLogger(loger)
	return &loger
}

/*
func (l *Logger) Level() core.LogLevel {
	return core.LOG_INFO
}

func (l *Logger) SetLevel(level core.LogLevel) (err error) {
	if level == core.LOG_ERR || level == core.LOG_WARNING {
		l.errorlog.SetLogLevel(filelogger.WARN)
	} else {
		l.accesslog.SetLogLevel(filelogger.INFO)
	}
	return nil
}
func (l *Logger) GetErrorlog() *log.Logger {
	return l.errorlog.GetLogger()
}
*/

func (l *Logger) GetAccessLogger() *log.Logger {
	return l.accesslog.GetLogger()
}

func (l Logger) Print(v ...interface{}) {
	l.accesslog.Println(v)
}

func (l *Logger) Info(v ...interface{}) (err error) {
	l.accesslog.Println(v)
	return
}

func (l *Logger) Infof(format string, v ...interface{}) (err error) {
	l.accesslog.Printf(format, v...)
	return
}

func (l *Logger) Debugf(format string, v ...interface{}) (err error) {
	l.accesslog.Printf(format, v...)
	return
}

func (l *Logger) Debug(v ...interface{}) (err error) {
	l.accesslog.Println(v)
	return
}

func (l *Logger) Err(v ...interface{}) (err error) {
	if len(v) == 1 {
		if reflect.TypeOf(v[0]).String() == `*errors.errorString` && v[0].(error).Error() == "sql: no rows in result set" {
			return
		}
	}
	l.accesslog.Println(v)
	return
}

func (l *Logger) Errf(format string, v ...interface{}) (err error) {
	l.accesslog.Printf(format, v...)
	return
}

func (l *Logger) Warning(v ...interface{}) (err error) {
	l.accesslog.Println(v)
	return
}
func (l *Logger) Warningf(format string, v ...interface{}) (err error) {
	l.accesslog.Printf(format, v...)
	return
}
