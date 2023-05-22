Feature: Login
  Background:
    * configure driver = { type: 'chrome', addOptions: ["--remote-allow-origins=*"] }
    * driver "http://sms.com/auth/login"
    * delay(3000)
  Scenario:
#    Nhap thong tin Email dang nhap = blank
    * input("//input[@name='password']", "123456")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(2000)

  Scenario:
#    Nhap thong tin Email dang nhap = spase
    * input("//input[@name='email']", " ")
    * input("//input[@name='password']", "123456")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(2000)

  Scenario:
#    Nhap thong tin Email dang nhap sai
    * input("//input[@name='email']", "lynv1@gmail.com")
    * input("//input[@name='password']", "123456")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(2000)

  Scenario:
#    Khong nhap thong tin mat khau sai
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "12345")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(2000)

  Scenario:
#    Nhap thong tin mat khau = blank
    * input("//input[@name='email']", "lynv11@gmail.com")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(2000)

  Scenario:
#    Nhap thong tin mat khau = space
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", " ")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(2000)

  Scenario:
#    Nhap thong tin Email dang nhap va mat khau sai
    * input("//input[@name='email']", "lynv1@gmail.com")
    * input("//input[@name='password']", "12345")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(2000)

  Scenario:
#    Nhap thong tin Email dang nhap va mat khau = blank
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(2000)

  Scenario:

#    Nhap thong tin Email dang nhap va mat khau = space
    * input("//input[@name='email']", " ")
    * input("//input[@name='password']", " ")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(2000)
