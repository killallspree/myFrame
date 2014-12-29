myFrame
=======

我的框架
myFrame
=======
1、良好的MVC分层模式，轻量级，高性能。

2、支持mysql一主多从数据库结构。借用一致性hash算法来分担从数据库查询压力，轻松应对高并发访问。

3、扩展性好。components（组件）、model（模型）文件夹中的类文件会自动注册到类库中，使用时，直接new 类名()即可。

4、使用灵活，更多惊喜等你发现。


demo

1、用mysql运行demo/docs/ini.sql文件。建立测试数据库。

2、打开demo/config/main.php，具体各项配置在文件中均有注释。

3、编译执行demo/index.php即可。

成功案例。

1、www.kutalk.com(真正实现一天建站)。

2、ol.liqucn.com(几十万pv/日，应付自如)。

3、www.***.com(百万pv，不在话下)[稍后公布-_-]。
