Feature: Login
  Background:
    * configure driver = { type: 'chrome', addOptions: ["--remote-allow-origins=*"] }
    * driver "http://sms.com/auth/login"
    * delay(3000)
  Scenario:
#  Kiem tra khi nhap ky tu la so
    * input("//input[@name='email']", "111111111")

#  Kiem tra khi nhap ky tu la ky tu chu thuong
#    * input("//input[@name='email']", "aaaaaaaa")

#  Kiem tra khi nhap ky tu la ky tu dac biet
#    * input("//input[@name='email']", "!@@##$%$%^^%=+")

#  Kiem tra khi nhap ky tu la space
#    * input("//input[@name='email']", "          ")

#  Kiem tra khi nhap ky tu la chu in hoa
#    * input("//input[@name='email']", "ABCDEFGHYK")

#  Kiem tra khi nhap ky tu la tieng viet co dau
#    * input("//input[@name='email']", "")

#  Kiem tra khi nhap ky tu la ky tu html, css, sql
#    * input("//input[@name='email']", "<tile>My Website<title>")
#    * input("//input[@name='email']", "'danhsach'")
#    * input("//input[@name='email']", "<script></script>")
    * delay(4000)