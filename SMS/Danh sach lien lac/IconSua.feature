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

#    Chon lien he can sua
    * delay(2000)
    * locateAll("//a[@class='mdc-button mdc-button--outlined icon-button mdc-button--dense mdc-ripple-upgraded']")[1].click()

    * delay(4000)