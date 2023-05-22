Feature: Dashboard
  Background:
    * configure driver = { type: 'chrome', addOptions: ["--remote-allow-origins=*"] }
    * driver "http://sms.com/auth/login"
    * delay(3000)
  Scenario:
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "123456")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * waitForUrl("http://sms.com/common/dashboard")
    * click("//span[@class='user-name']")
    * delay(2000)
    * click("//h6[@class='item-subject font-weight-normal']")
    * delay(3000)
    * input("//input[@id='input-sms-prefix']","N")
    * click("//a[contains(.,'Lưu & Thoát')]")
    * click("//a[@class='mdc-drawer-link-second' and @href='http://sms.com/contact/sms-group']")
    * click("//input[@class='mdc-checkbox__native-control mdc-checkbox__contact-id']")
    * click("//a[@class='mdc-button mdc-button--unelevated mdc-button--dense mdc-ripple-upgraded']")
    * delay(4000)