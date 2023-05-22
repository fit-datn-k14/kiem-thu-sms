Feature: Contact
  Background:
    * configure driver = { type: 'chrome', addOptions: ["--remote-allow-origins=*"] }
    * driver "http://sms.com/auth/login"
    * delay(3000)
  Scenario:
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "123456")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * click("//a[@class='mdc-drawer-link-second' and @href='http://sms.com/contact/contact']")
    * click("//a[@class='mdc-button mdc-button--outlined mdc-button--dense mdc-ripple-upgraded']")

#    Ho va ten = Ky tu thuong
    * input("//input[@id='input-full-name']","Nguyễn Tùng Dương")

#    Ho va ten = Ky tu dac biet
#    * input("//input[@id='input-full-name']","!@#$%^&*()")

#    Ho va ten = Chu so
#    * input("//input[@id='input-full-name']","123456789")

#    Ho va ten = hmtl, css, java,..
#    * input("//input[@id='input-full-name']","<!DOCTYPE html>")

    * delay(4000)
