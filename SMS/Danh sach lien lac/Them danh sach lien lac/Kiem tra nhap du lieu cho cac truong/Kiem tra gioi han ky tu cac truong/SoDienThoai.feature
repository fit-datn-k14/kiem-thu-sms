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

#    So dien thoai = blank
#    * input("//input[@id='input-phone']","")

#    0 < So dien thoai < 8
#    * input("//input[@id='input-phone']","1111111")

#    So dien thoai = 8
#    * input("//input[@id='input-phone']","11111111")

#    8 < So dien thoai < 23
#    * input("//input[@id='input-phone']","1111111111111111111111")

#    So dien thoai = 23
#    * input("//input[@id='input-phone']","11111111111111111111111")

#    So dien thoai > 23
    * input("//input[@id='input-phone']","111111111111111111111111111")

    * click("//a[contains(.,'Lưu & Thoát')]")
    * delay(4000)
