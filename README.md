# 概要
`mattermost-load-tester`は、[web-server-load-tester](https://github.com/tmori/web-server-load-tester)を利用して、[Mattermost API](https://api.mattermost.com/)で Mattermost サーバーの負荷テストを行うツールです。

Mattermost APIのコール処理は、以下のドライバを利用しています。

- [Laravel PHP ドライバ](https://github.com/gnello/laravel-mattermost-driver)

負荷テストで計測する Mattermost のAPIは以下です。

- [チャネルへのメッセージ投稿](https://api.mattermost.com/#tag/posts/operation/CreatePost)
- [チャネルのメッセージ参照](https://api.mattermost.com/#tag/posts/operation/GetPostsForChannel)

また、負荷テストに必要なMattermostのテストデータは [mattermost-initializer](https://github.com/tmori/mattermost-initializer)を利用して作成します。

# テスト項目

[web-server-load-tester](https://github.com/tmori/web-server-load-tester)の機能を利用することで、テスト項目はCSVファイルで作成し、そのままテストを自動実行させます。

## チャネルへのメッセージ投稿

最大で２００多重で２５６文字のランダム文字列をPOSTし続けるテストです。

https://github.com/tmori/mattermost-load-tester/blob/main/php/hako/load-test-resource/test-item/mattermost-create-post-item.csv

## チャネルへのメッセージ参照

最大で２００多重でPOSTしたメッセージを取得し続けるテストです。

https://github.com/tmori/mattermost-load-tester/blob/main/php/hako/load-test-resource/test-item/mattermost-get-post-item.csv

# テスト結果

テスト結果はこちらに自動的に格納されます。
なお、格納されているデータは、以下の環境で測定したものです。

* CPU: 8コア
* メモリ：16GB
* ディスク：15GB

ちなみに、多重テスト実施すると、500, 401, 404 のエラーが返る場合があります。
Mattermostのconfig.json の 以下のパラメータを調整することで回避することができます。

https://docs.mattermost.com/configure/rate-limiting-configuration-settings.html

## チャネルへのメッセージ投稿

https://github.com/tmori/mattermost-load-tester/blob/main/php/hako/load-test-resource/test-result/mattermost/mattermost-create-post-item-result.csv

## チャネルへのメッセージ参照

https://github.com/tmori/mattermost-load-tester/blob/main/php/hako/load-test-resource/test-result/mattermost/mattermost-get-post-item-result.csv

# 前提とする環境

- Mattermost がインストールされていること
  - [MattermostをUbuntu20.0.4 サーバにインストールする手順](https://qiita.com/kanetugu2018/items/51cdab279d81ae06aa70)
- MattermostのURLは、https でアクセスできること
- Linux環境で docker-compose が利用できる環境であること(Windows WSLは対象外)

# インストール手順

docker-compose.yml の [MATTERMOST_URL](https://github.com/tmori/tutorial_mattermost/blob/a1918b7ccb10a9f3338ac2e5a48a9e0e09705064/docker-compose.yml#L10) を適切なものに変更してください。


```
docker-compose build
```

```
docker-compose up -d
```

```
docker-compose exec php /bin/bash
```

```
bash install.bash
```

テスト自動化していますので、パスワードなしで ssh で Mattermostサーバーマシンにログインできる必要があります。
以下の記事が参考になります。

https://blog.apar.jp/linux/5336/


# 環境変数の設定

本ツールを実行するには以下の環境変数設定ファイル(env.bash)を編集する必要があります。

* ./mattermost-initializer/db-backup-restore/env/env.bash
* ./mattermost-initializer/env/env.bash
* ./web-server-load-tester/env/env.bash

設定内容は、各リポジトリのREADMEを参照ください。

ただし、`./web-server-load-tester/env/env.bash`については、以下の項目は本ツール向けに追加しておりますので、環境に応じて変更してください。

* TEST_TARGET_TOOL_DIR
  * Mattermost サーバー側の web-server-load-tester のディレクトリパスです。
* MATTERMOST_TOOL_DIR
  * Mattermost サーバー側の mattermost-initializer のディレクトリパスです。
* MATTERMOST_ROOT_USER
  * Mattermost の管理者用ユーザIDです。
* MATTERMOST_ROOT_PASSWD
  * Mattermost の管理者用ユーザIDのパスワードです。
* MATTERMOST_DB_BKP_TOOL_DIR
  *  Mattermost サーバー側のdb-backup-restoreのディレクトリパスです。
* MATTERMOST_DB_BKP_DIR
  *  Mattermost サーバー側のバックアップファイル配置ディレクトリパスです。


# テスト実行方法

docker コンテナに入り、以下のディレクトリに移動します。

★注意：ssh の設定は、コンテナからログアウトすると消えてしまいますので、毎回、実施しないといけないです。。

```
cd /root/workspace/web-server-load-tester
```

## チャネルへのメッセージ投稿のテストを実行する場合

```
bash ../load-test-resource/test-scripts/create-post-test.bash
```

## チャネルへのメッセージ参照のテストを実行する場合

```
bash ../load-test-resource/test-scripts/get-post-test.bash
```


# 終了方法

```
docker-compose down
```



# Mattermost PHPドライバ確認ツール

以下、PHPドライバ確認用のツールの説明です。個別に機能確認したい場合は、ご利用ください。

## ユーザのログインサンプル

ソースファイル：[MattermostLogin.php](https://github.com/tmori/tutorial_mattermost/blob/main/php/hako/apps/MattermostLogin.php)

実行方法：
```
php artisan mattermost:login <login_id> <passward>
```


## ユーザの新規追加サンプル

ソースファイル：[MattermostCreateUser.php](https://github.com/tmori/tutorial_mattermost/blob/main/php/hako/apps/MattermostCreateUser.php)


事前に、管理者のユーザIDとパスワードを環境変数として登録してください。

設定例：
```
export MATTERMOST_ROOT_USER=root
export MATTERMOST_ROOT_PASSWD=hogehoge-Hoge
```

実行方法：
```
php artisan mattermost:create_user <username> <passward>
```

参照：
https://api.mattermost.com/#tag/users/operation/CreateUser


## チームの新規追加サンプル

ソースファイル：[MattermostCreateTeam.php](https://github.com/tmori/tutorial_mattermost/blob/main/php/hako/apps/MattermostCreateTeam.php)


事前に、管理者のユーザIDとパスワードを環境変数として登録してください。

設定例：
```
export MATTERMOST_ROOT_USER=root
export MATTERMOST_ROOT_PASSWD=hogehoge-Hoge
```

実行方法：
```
php artisan mattermost:create_team <name> <display_name> <type>
```

参照：
https://api.mattermost.com/#tag/teams/operation/CreateTeam


## チャネルの新規追加サンプル

ソースファイル：[MattermostCreateChannel.php](https://github.com/tmori/tutorial_mattermost/blob/main/php/hako/apps/MattermostCreateChannel.php)


事前に、管理者のユーザIDとパスワードを環境変数として登録してください。

設定例：
```
export MATTERMOST_ROOT_USER=root
export MATTERMOST_ROOT_PASSWD=hogehoge-Hoge
```

実行方法：
```
php artisan mattermost:create_channel <team_name> <channel_name> <display_name> <type>
```

参照：
https://api.mattermost.com/#tag/channels/operation/CreateChannel



## チームへのユーザ登録サンプル

ソースファイル：[MattermostAddUserToTeam.php](https://github.com/tmori/tutorial_mattermost/blob/main/php/hako/apps/MattermostAddUserToTeam.php)


事前に、管理者のユーザIDとパスワードを環境変数として登録してください。

設定例：
```
export MATTERMOST_ROOT_USER=root
export MATTERMOST_ROOT_PASSWD=hogehoge-Hoge
```

実行方法：
```
php artisan mattermost:add_user_to_team <team_name> <username>
```

参照：
https://api.mattermost.com/#tag/teams/operation/AddTeamMember


# チャネルへのユーザ登録サンプル

ソースファイル：[MattermostAddUserToChannel.php](https://github.com/tmori/tutorial_mattermost/blob/main/php/hako/apps/MattermostAddUserToChannel.php)


事前に、管理者のユーザIDとパスワードを環境変数として登録してください。

設定例：
```
export MATTERMOST_ROOT_USER=root
export MATTERMOST_ROOT_PASSWD=hogehoge-Hoge
```

実行方法：
```
php artisan mattermost:add_user_to_channel <team_name> <channel_name> <username>
```

参照：
https://api.mattermost.com/#tag/channels/operation/AddChannelMember


## チャネルへのメッセージ投稿サンプル

ソースファイル：[MattermostCreatePost.php](https://github.com/tmori/tutorial_mattermost/blob/main/php/hako/apps/MattermostCreatePost.php)


事前に、管理者のユーザIDとパスワードを環境変数として登録してください。

実行方法：
```
php artisan mattermost:create_post <username> <passwd> <team_name> <channel_name> <message>
```

参照：
https://api.mattermost.com/#tag/posts/operation/CreatePost
