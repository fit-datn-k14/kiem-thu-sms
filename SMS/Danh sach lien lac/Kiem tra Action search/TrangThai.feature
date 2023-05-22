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
    #    Trang thai = blank
    * click("//div[@class='mdc-select__selected-text']")
    * click("//li[@class='mdc-list-item mdc-list-item--selected']")
    * click("//button[@id='button-filter']")
    * delay(2000)

#    Trang thai = Kich hoat
    * click("//div[@class='mdc-select__selected-text']")
    * click("//li[@class='mdc-list-item' and @data-value='1']")
    * click("//button[@id='button-filter']")
    * delay(2000)

#    Trang thai = Vo hieu hoa
    * click("//div[@class='mdc-select__selected-text']")
    * click("//li[@class='mdc-list-item' and @data-value='0']")
    * click("//button[@id='button-filter']")
    * delay(4000)