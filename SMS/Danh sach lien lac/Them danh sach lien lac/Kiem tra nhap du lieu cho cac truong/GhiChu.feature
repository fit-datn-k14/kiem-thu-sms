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

#    Ghi chu = Ky tu thuong
    * input("//textarea[@id='input-note']","Khách hàng quan trọng")

#    Ghi chu = Ky tu dac biet
#    * input("//textarea[@id='input-note']","!@#$%^&*()")

#    Ghi chu = Chu so
#    * input("//textarea[@id='input-note']","1234567890")

#    Ghi chu = hmtl, css, java,..
#    * input("//textarea[@id='input-note']","<!DOCTYPE html>")

    * delay(4000)
