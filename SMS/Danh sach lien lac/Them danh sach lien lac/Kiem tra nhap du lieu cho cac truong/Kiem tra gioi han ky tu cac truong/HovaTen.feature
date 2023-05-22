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

#    Ho va ten = blank
#    * input("//input[@id='input-full-name']","")

#    0 < Ho va ten < 63
#    * input("//input[@id='input-full-name']","Thùy ")

#    Ho va ten = 63
#    * input("//input[@id='input-full-name']","Đây là tên của mình nhé các bạn yêu, chào buổi sáng vui vẻ nhé!")

#    Ho va ten > 63
    * input("//input[@id='input-full-name']","aaaaaaaaaaaaaaa Đây là tên của mình nhé các bạn yêu, chào buổi sáng vui vẻ nhé các bạn !!")

    * click("//a[contains(.,'Lưu & Thoát')]")
    * delay(4000)
