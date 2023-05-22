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
    * waitFor("//a[@class='mdc-button mdc-button--outlined mdc-button--dense mdc-ripple-upgraded']").click()
    * input("//input[@id='input-full-name']","Nguyễn Tùng Dương")
    * input("//textarea[@id='input-address']","Hòa Bình")
    * input("//input[@id='input-phone']","035 6469354")
    * input("//textarea[@id='input-note']","Khách sỉ khu vực Hòa Bình")
    * delay(3000)
    * click("//a[contains(.,'Lưu & Thoát')]")
    * delay(3000)
    * click("//a[@href='http://sms.com/common/dashboard']")
    * delay(3000)
