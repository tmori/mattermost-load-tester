# 概要
本リポジトリでは、以下を整理しています。

- [x] [Mattermost API](https://api.mattermost.com/) を [Laravel PHP ドライバ](https://github.com/gnello/laravel-mattermost-driver)を使って実行するための方法

サンプルアプリとしては、以下の laravel コマンドを用意しています。

- [ ] ユーザのログインサンプル
- [ ] ユーザの新規追加サンプル
- [ ] チームの新規追加サンプル
- [ ] チャネルの新規追加サンプル
- [ ] チームへのユーザ登録サンプル
- [ ] チャネルへのユーザ登録サンプル
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

# サンプルアプリ実行手順

TODO

# 終了方法

```
docker-compose down
```
