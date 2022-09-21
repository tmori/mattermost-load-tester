# 概要
本リポジトリでは、以下を整理しています。

- [x] [Mattermost API](https://api.mattermost.com/) を [Laravel PHP ドライバ](https://github.com/gnello/laravel-mattermost-driver)を使って実行するための方法

サンプルアプリとしては、以下の laravel コマンドを用意しています。

- [X] ユーザのログインサンプル
- [X] ユーザの新規追加サンプル
- [X] チームの新規追加サンプル
- [X] チャネルの新規追加サンプル
- [X] チームへのユーザ登録サンプル
- [X] チャネルへのユーザ登録サンプル
- [ ] チャネルへのメッセージ投稿サンプル

# 前提とする環境

- Mattermost がインストールされていること
  - [MattermostをUbuntu20.0.4 サーバにインストールする手順](https://qiita.com/kanetugu2018/items/51cdab279d81ae06aa70)
- MattermostのURLは、https でアクセスできること
- docker-compose が利用できる環境であること


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


# 終了方法

```
docker-compose down
```

# ユーザのログインサンプル

ソースファイル：[MattermostLogin.php](https://github.com/tmori/tutorial_mattermost/blob/main/php/hako/apps/MattermostLogin.php)

実行方法：
```
php artisan mattermost:login <login_id> <passward>
```


# ユーザの新規追加サンプル

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


# チームの新規追加サンプル

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


# チャネルの新規追加サンプル

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



# チームへのユーザ登録サンプル

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
