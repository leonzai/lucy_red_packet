# php 版红包算法

参考腾讯红包算法。

功能点：

1. 指定红包金额、数量发红包
2. 指定红包金额、数量，再指定某个人发送指定金额的红包

### 红包数量 5 个、金额 5 元

```php

测试数据：
第 1 个0.85元
第 2 个1.63元
第 3 个1.46元
第 4 个1.03元
第 5 个0.03元
====================================================测试数据：
第 1 个0.74元
第 2 个1.3元
第 3 个1.56元
第 4 个0.41元
第 5 个0.99元
====================================================测试数据：
第 1 个0.98元
第 2 个0.44元
第 3 个1.37元
第 4 个1.31元
第 5 个0.9元
====================================================测试数据：
第 1 个0.09元
第 2 个0.3元
第 3 个2.14元
第 4 个2.12元
第 5 个0.35元
====================================================测试数据：
第 1 个0.4元
第 2 个1.97元
第 3 个1.47元
第 4 个0.93元
第 5 个0.23元
```

红包数量 5 个、金额 5 元、指定第 4 个人 3 元

```php
测试数据：
第 1 个0.12元
第 2 个0.17元
第 3 个1.43元
第 4 个3元
第 5 个0.28元
====================================================测试数据：
第 1 个0.78元
第 2 个0.66元
第 3 个0.3元
第 4 个3元
第 5 个0.26元
====================================================测试数据：
第 1 个0.85元
第 2 个0.27元
第 3 个0.16元
第 4 个3元
第 5 个0.72元
====================================================测试数据：
第 1 个0.86元
第 2 个0.47元
第 3 个0.01元
第 4 个3元
第 5 个0.66元
====================================================测试数据：
第 1 个0.8元
第 2 个0.15元
第 3 个0.77元
第 4 个3元
第 5 个0.28元
```

