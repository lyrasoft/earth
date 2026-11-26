# LYRASOFT Package 開發指引

基本套件開發指引請見: `../../../windwalker/references/development-core/package-development.md`

這裡是 LYASOFT 的延伸處理方案。

## 根目錄各類開發文件

- `.gitignore`，可參考這份共用版本：<https://github.com/lyrasoft/luna/blob/master/.gitignore>
- `README.md`，可先放上套件標題即可
- `VERSION` `0.1.0` 起始

## 自動更新版本號

為了方便管理套件版本，可使用預先準備的發布指令更新版本號與 Git tag。

先在套件根目錄建立 `VERSION` 檔案，填入初始版本號，例如 `0.1.0`：

接著在套件根目錄建立 `bin/`，並在該目錄下載 `Console.php` 與 `release.php`：

```shell
wget https://raw.githubusercontent.com/asika32764/php-simple-console/master/src/Console.php
wget https://raw.githubusercontent.com/asika32764/php-simple-console/refs/heads/master/bin/release.php

chmod +x release.php
```

或使用 `curl`：

```shell
curl https://raw.githubusercontent.com/asika32764/php-simple-console/master/src/Console.php -o Console.php
curl https://raw.githubusercontent.com/asika32764/php-simple-console/refs/heads/master/bin/release.php -o release.php

chmod +x release.php
```

### 更新版號的指令

程式更新完成、準備發布新版本時，在套件根目錄執行：

```shell
php bin/release.php patch
```

其他參數：

```shell
php bin/release.php -h       # 顯示說明
php bin/release.php patch    # 更新 patch 版本
php bin/release.php minor    # 更新 minor 版本
php bin/release.php major    # 更新 major 版本
php bin/release.php x.x.x    # 指定版本號
```
