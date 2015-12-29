cd /d %~dp0

::下面两个regsvr32，使用一个即可，不使用的请用::注释。
::实际安装注册的时候，应使用无弹框的注册方式。

::注册控件，有弹框
regsvr32 ".\seperate.ocx"

::注册控件，无弹框
::regsvr32 /s ".\seperate.ocx"