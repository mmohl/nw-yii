<?php

namespace app\helpers;

use yii\helpers\Url;

class Breadcrumbs
{
  public static function make($lists, $title)
  {
    // dd($lists);
    $mappedLists = collect($lists)->map(function ($list) {
      if (is_array($list)) {
        return '<li class="breadcrumb-item"><a href="' . Url::to($list['url']) . '" class="breadcrumb-link">' . $list['label'] . '</a></li>';
      } else {
        return '<li class="breadcrumb-item active" aria-current="page">' . $list . '</li>';
      }
    })->implode('');

    return '<div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="page-header">
                      <h2 class="pageheader-title">' . $title . '</h2>
                      <div class="page-breadcrumb">
                          <nav aria-label="breadcrumb">
                              <ol class="breadcrumb">
                                  ' . $mappedLists . '
                              </ol>
                          </nav>
                      </div>
                  </div>
              </div>
          </div>';
  }
}
