Feature: Dashboard
  Background:
    * configure driver = { type: 'chrome', addOptions: ["--remote-allow-origins=*"] }
    * driver "http://sms.com/auth/login"
    * delay(3000)
  Scenario:
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "123456")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * click("//a[@class='mdc-expansion-panel-link' and @href='javascript:void(0)']")
    * click("//a[@class='mdc-drawer-link-second' and @href='http://sms.com/customer/recharge-history']")
    * delay(4000)