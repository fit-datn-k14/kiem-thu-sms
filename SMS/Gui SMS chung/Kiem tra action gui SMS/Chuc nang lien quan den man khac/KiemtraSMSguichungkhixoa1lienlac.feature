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
    * locateAll("//a[@class='mdc-button mdc-button--outlined icon-button mdc-button--dense outlined-button--secondary mdc-ripple-upgraded']")[].click()
    * delay(2000)
    * click("//a[@class='mdc-button mdc-button--outlined outlined-button--secondary mdc-button--dense mdc-ripple-upgraded']")
    * delay(2000)
    * click("//a[@class='mdc-drawer-link-second' and @href='http://sms.com/contact/sms-group']")
    * delay(2000)
