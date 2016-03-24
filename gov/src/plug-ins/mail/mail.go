package mail

import (
	"encoding/base64"
	"fmt"
	"net/mail"
	"net/smtp"
)

/*
func main() {
	Mailto("48707261@qq.com")
}
*/
type MailStmp struct {
	Host      string
	Port      string
	Mail      string
	LoginName string
	LoginPwd  string
	FromName  string
}

type MailTo struct {
	Mail     string
	UserName string
	Subject  string
	Message  string
	Body     string
}

/*
host := "smtp.qq.com"
	email := "noreply@lunwensl.com"
	password := "Qe9dAc5rZ1fW7vS5"//Qe9dAc5rZ1fW7vS5
	toEmail := emailto //"48707261@qq.com"
*/

func Mailto(stmp *MailStmp, emailto *MailTo) error {
	b64 := base64.NewEncoding("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/")
	from := mail.Address{stmp.FromName, stmp.Mail}
	to := mail.Address{emailto.UserName, emailto.Mail}
	header := make(map[string]string)
	header["From"] = from.String()
	header["To"] = to.String()
	header["Subject"] = fmt.Sprintf("=?UTF-8?B?%s?=", b64.EncodeToString([]byte(emailto.Subject)))
	header["MIME-Version"] = "1.0"
	header["Content-Type"] = "text/html; charset=UTF-8"
	header["Content-Transfer-Encoding"] = "base64"
	/*
		var buf bytes.Buffer
		tp, err := template.ParseFiles("./mailtpl/regmail.html")
		if err != nil {
			panic(err)
		}
		data := struct {
			Name string
			Url  string
		}{
			emailtoname,
			"http://sdfsfsfsf",
		}
		tp.Execute(&buf, data)
	*/
	message := ""
	for k, v := range header {
		message += fmt.Sprintf("%s: %s\r\n", k, v)
	}
	message += "\r\n" + b64.EncodeToString([]byte(emailto.Body))
	auth := smtp.PlainAuth(
		"",
		stmp.Mail,
		stmp.LoginPwd,
		stmp.Host,
	)
	err := smtp.SendMail(
		stmp.Host+":"+stmp.Port,
		auth,
		stmp.Mail,
		[]string{to.Address},
		[]byte(message),
	)
	if err != nil {
		return err
	}
	return nil
}
