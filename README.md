#　これは何のリポジトリですか？
* FuelPHPのスキャフォールドコマンドをカスタマイズしたリポジトリです

## 推奨バージョン
* FeulPHP Version: 1.7.1
* PHP 5.5.6
* テンプレートエンジン：twig

## インストール
サブモジュールつきでcloneする

* 1．git clone --recursive https://github.com/GitZiko/customize_fuelphp.git [リポジトリ名]
* 2.git submodule foreach 'git checkout 1.7/master'
* 3.git submodule update

## composer update
* 1.php composer.phar self-update
* 2.php composer.phar update

## oilコマンド実行
拡張したoilコマンドはfuel/app以下に配置してあります。

* $ cd ~/[リポジトリ名]/fuel/app/
* $ php oil g scaffold [テーブル名] [カラム名]:[型] --singular
* $ php oil refine migrate
