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
    * click("//button[@class='material-icons mdc-top-app-bar__navigation-icon mdc-icon-button sidebar-toggler']")
    * delay(2000)
    * click("//button[@class='material-icons mdc-top-app-bar__navigation-icon mdc-icon-button sidebar-toggler']")
    * delay(2000)