<?php

const MESSAGES = [
  'success' => [
    'auth' => [
      'login' => 'ログインしました。',
      'logout' => 'ログアウトしました。',
      'register' => '新規登録が完了しました。',
    ],
    'thread' => [
      'created' => '投稿を作成しました。',
      'updated' => '投稿を更新しました。',
      'deleted' => '投稿を削除しました。',
    ],
    'user' => [
      'created' => 'アカウントを作成しました。',
      'updated' => 'アカウントを更新しました。',
      'deleted' => 'アカウントを削除しました。',
    ],
  ],
  'error' => [
    'common' => [
      'required' => 'を入力してください。',
      'select' => 'を選択してください。',
    ],
    'auth' => [
      'require_login' => 'ログインしてください。',
      'login_failed' => 'メールアドレスまたはパスワードが間違っています。',
      'register_failed' => '新規登録に失敗しました。',
      'unauthorized' => 'この操作を実行する権限がありません。',
    ],
    'user' => [
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
      'not_found' => '投稿が見つかりません。',
      'not_owner' => 'この投稿の編集は許可されていません。',
      'create_failed' => '投稿の作成に失敗しました。',
      'update_failed' => '投稿の更新に失敗しました。',
      'delete_failed' => '投稿の削除に失敗しました。',
      'title_max_length' => 'タイトルは255文字以内で入力してください。',
      'content_max_length' => '内容は1000文字以内で入力してください。',
    ],
    'security' => [
      'invalid_csrf' => 'セキュリティトークンが無効です。ページを再読み込みしてください。',
    ],
    'database' => [
      'connection_error' => 'データベース接続エラーが発生しました。管理者にお問い合わせください。',
    ],
  ]
];