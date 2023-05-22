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
    * input("//input[@id='input-full-name']","Nguyễn Thị Lan")
    * input("//textarea[@id='input-address']","Hòa Bình")
    * input("//input[@id='input-phone']","1234567890")
    * input("//textarea[@id='input-note']","Khách hàng quan trọng")
    * delay(3000)
    * click("//a[contains(.,'Lưu & Thoát')]")
    * delay(3000)
    * click("//a[@class='mdc-drawer-link-second' and @href='http://sms.com/contact/sms-group']")
    * delay(3000)
