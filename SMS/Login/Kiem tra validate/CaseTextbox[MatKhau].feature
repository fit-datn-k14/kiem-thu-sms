Feature: Login
  Background:
    * configure driver = { type: 'chrome', addOptions: ["--remote-allow-origins=*"] }
    * driver "http://sms.com/auth/login"
    * delay(3000)
  Scenario:
#  Kiem tra khi nhap ky tu la so
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "111111111")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(3000)

  Scenario:
#  Kiem tra khi nhap ky tu la ky tu chu thuong
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "aaaaaaaa")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(3000)

  Scenario:
#  Kiem tra khi nhap ky tu la ky tu dac biet
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "!@@##$%$%^^%=+")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(3000)

  Scenario:
#  Kiem tra khi nhap ky tu la space
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "          ")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(3000)

  Scenario:
#  Kiem tra khi nhap ky tu la chu in hoa
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "ABCDEFGHYK")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(3000)

  Scenario:
#  Kiem tra khi nhap ky tu la tieng viet co dau
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(3000)

  Scenario:
#  Kiem tra khi nhap ky tu la ky tu html, css, sql
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "<tile>My Website<title>")
#    * input("//input[@name='password']", "'danhsach'")
#    * input("//input[@name='password']", "<script></script>")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * delay(3000)

  Scenario:
#    Kiem tra so ky tu khi nhap mat khau [8;64]
    * input("//input[@name='email']", "lynv11@gmail.com")
#    * input("//input[@name='password']", "1234567")
#    * input("//input[@name='password']", "12345678")
#    * input("//input[@name='password']", "111111111111111111111111111111111111")
#    * input("//input[@name='password']", "1111111111111111111111111111111111111111111111111111111111111111")
    * input("//input[@name='password']", "11111111111111111111111111111111111111111111111111111111111111111")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")

    * delay(4000)