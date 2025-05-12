<?php

const MESSAGES = [
  'success' => [
    'auth' => [
      'login' => 'ログインしました。',
      'logout' => 'ログアウトしました。',
      'signup' => '新規登録が完了しました。',
    ],
    'post' => [
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
    'auth' => [
      'require_login' => 'ログインしてください。',
      'login_failed' => 'メールアドレスまたはパスワードが間違っています。',
      'signup_failed' => '新規登録に失敗しました。',
      'unauthorized' => 'この操作を実行する権限がありません。',
    ],
    'user' => [
      'username_taken' => 'このユーザー名は既に使われています。',
      'email_taken' => 'このメールアドレスは既に使われています。',
      'username_required' => 'ユーザー名を入力してください。',
      'email_required' => 'メールアドレスを入力してください。',
      'email_invalid' => '正しいメールアドレスを入力してください。',
    ],
    'password' => [
      'mismatch' => 'パスワードが一致しません。',
      'too_short' => 'パスワードは8文字以上で入力してください。',
      'required' => 'パスワードを入力してください。',
    ],
    'post' => [
      'not_found' => '投稿が見つかりません。',
      'not_owner' => 'この投稿の編集は許可されていません。',
      'create_failed' => '投稿の作成に失敗しました。',
      'update_failed' => '投稿の更新に失敗しました。',
      'delete_failed' => '投稿の削除に失敗しました。',
      'title_required' => 'タイトルを入力してください。',
      'title_max_length' => 'タイトルは255文字以内で入力してください。',
      'content_required' => '内容を入力してください。',
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