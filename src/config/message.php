<?php

const MESSAGES = [
  'success' => [
    'auth' => [
      'login' => 'ログインしました。',
      'logout' => 'ログアウトしました。',
      'register' => '新規登録が完了しました。',
    ],
    'thread' => [
      'created' => 'スレッドを作成しました。',
      'updated' => 'スレッドを更新しました。',
      'deleted' => 'スレッドを削除しました。',
    ],
    'user' => [
      'created' => 'アカウントを作成しました。',
      'updated' => 'アカウントを更新しました。',
      'deleted' => 'アカウントを削除しました。',
    ],
    'comment' => [
      'created' => 'コメントを投稿しました。',
      'deleted' => 'コメントを削除しました。',
    ],
  ],
  'error' => [
    'common' => [
      'required' => 'を入力してください。',
      'select' => 'を選択してください。',
      'max_length' => 'は{max_length}文字以内で入力してください。',
      'min_length' => 'は{min_length}文字以上で入力してください。',
    ],
    'auth' => [
      'require_login' => 'ログインしてください。',
      'login_failed' => 'ログインに失敗しました。',
      'register_failed' => '新規登録に失敗しました。',
      'unauthorized' => 'この操作を実行する権限がありません。',
      'require_admin_login' => '管理者としてログインしてください。',
    ],
    'user' => [
      'invalid_id' => '不正なIDです。',
      'not_found' => 'ユーザーが見つかりません。',
      'last_name_max_length' => '姓は255文字以内で入力してください。',
      'first_name_max_length' => '名は255文字以内で入力してください。',
      'address_max_length' => '住所は255文字以内で入力してください。',
      'email_taken' => 'このメールアドレスは既に使われています。',
      'email_invalid' => '正しいメールアドレスを入力してください。',
    ],
    'password' => [
      'mismatch' => 'パスワードが一致しません。',
      'too_short' => 'パスワードは8文字以上で入力してください。',
      'required' => 'パスワードを入力してください。',
    ],
    'thread' => [
      'not_found' => 'スレッドが見つかりません。',
      'not_owner' => 'このスレッドの編集は許可されていません。',
      'create_failed' => 'スレッドの作成に失敗しました。',
      'update_failed' => 'スレッドの更新に失敗しました。',
      'delete_failed' => 'スレッドの削除に失敗しました。',
      'title_max_length' => 'タイトルは255文字以内で入力してください。',
    ],
    'security' => [
      'invalid_csrf' => 'セキュリティトークンが無効です。ページを再読み込みしてください。',
    ],
    'database' => [
      'connection_error' => 'データベース接続エラーが発生しました。管理者にお問い合わせください。',
    ],
    'comment' => [
      'delete_failed' => 'コメントの削除に失敗しました。',
      'not_found' => 'コメントが見つかりません。',
    ],
  ]
];