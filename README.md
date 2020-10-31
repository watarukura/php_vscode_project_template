# php_vscode_project_template

## 概要

- PHPをVS Codeで書く
- AutoSave + AutoFormat
  - デフォルトのルールを使う
  - ルールは最初からstrictに
    - 途中からstrictにするのは難しいので
- GitHub ActionsでCI

## その他

- PHP以外はgithub/super-linterでlintする
  - 必要なVALIDATE_RULEだけtrueにする
