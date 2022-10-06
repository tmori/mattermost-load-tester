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
