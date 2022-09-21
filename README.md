# 概要
TODO

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
