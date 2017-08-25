<?php
return [
  'types' => ['upvote', 'downvote'],
  'default_type' => 'upvote',

  'use_cache' => true,
  'cache_columns' => [
    'upvote' => 'upvotes',
    'downvote' => 'downvotes',
  ],
];
