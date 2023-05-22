Feature: Sent SMS general
  Background:
    * configure driver = { type: 'chrome', addOptions: ["--remote-allow-origins=*"] }
    * driver "http://sms.com/auth/login"
    * delay(3000)
  Scenario:
    * input("//input[@name='email']", "lynv11@gmail.com")
    * input("//input[@name='password']", "123456")
    * click("//button[@type='submit' and @class='mdc-button mdc-button--raised w-100 mdc-ripple-upgraded']")
    * click("//a[@class='mdc-drawer-link-second' and @href='http://sms.com/contact/sms-group']")
    * click("//input[@class='mdc-checkbox__native-control mdc-checkbox__contact-id']")
    * delay(1000)
    * click("//a[@class='mdc-button mdc-button--unelevated mdc-button--dense mdc-ripple-upgraded']")
#    Noi dung = blank
    * input("//textarea[@class='form-control']", "")

#     Noi dung = Space
#    * input("//textarea[@class='form-control']", "  ")

#     Noi dung = ky tu chu so
#    * input("//textarea[@class='form-control']", "123456")

#    Noi dung = ky tu dac biet
#    * input("//textarea[@class='form-control']", "!@#$%^&*()(")

#    Noi dung = The html, java, css
#    * input("//textarea[@class='form-control']", "<tile>My Website<title> <script></script>")

    * click("//a[@id='do__send_message']")
    * delay(5000)
