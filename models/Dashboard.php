<?php

namespace app\models;

use Carbon\Carbon;

class Dashboard
{
  const RANGE_DAILY = 'daily';
  const RANGE_WEEKLY = 'weekly';
  const RANGE_MONTHLY = 'monthly';
  const RANGE_QUARTERLY = 'quarterly';
  const RANGE_HALF_YEARLY = 'half_yearly';
  const RANGE_ANNUALY = 'annualy';

  public static function getDashboardInfo($range = Dashboard::RANGE_DAILY)
  {
    $tmp = [
      'salesInfo' => self::getSalesReport($range),
      'newCustomers' => self::getCustomers($range, true),
      'customers' => self::getCustomers($range),
      'totalOrders' => self::getTotalOrders($range),
      'unpaidOrders' => self::getUnpaidOrders(),
      'sellItems' => self::getSellItems($range)
    ];

    return $tmp;
  }

  private static function getTotalOrders($range)
  {
    $query = Order::find();
    $startTime = null;
    $endTime = null;
    $tmpCarbon = Carbon::now();

    if ($range == Dashboard::RANGE_DAILY) {
      $startTime = Carbon::today()->format('Y-m-d');
      $endTime = Carbon::today()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_WEEKLY) {
      $startTime = $tmpCarbon->startOfWeek()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfWeek()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_MONTHLY) {
      $startTime = $tmpCarbon->startOfMonth()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfMonth()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_QUARTERLY) {
      $startTime = $tmpCarbon->startOfQuarter()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfQuarter()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_HALF_YEARLY) {
      $startTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 7 : 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 12 : 6, 31);
    } else if ($range == Dashboard::RANGE_ANNUALY) {
      $startTime = Carbon::createFromDate(date('Y'), 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), 12, 31);
    }

    $query = $query->with(['items'])->where(['between', 'date', $startTime, $endTime]);

    $resultToday = collect($query->all())->count();
    $resultYesterday = self::getTotalOrdersComparator($range);
    $sum = $resultToday + $resultYesterday;

    return ['comparator' => (($resultToday/$sum) * 100) - (($resultYesterday/$sum)*100), 'value' => $resultToday];
  }

  private static function getCustomers($range, $isNew = false)
  {
    $query = Order::find()->select('ordered_by');
    $startTime = null;
    $endTime = null;
    $tmpCarbon = Carbon::now();

    if ($range == Dashboard::RANGE_DAILY) {
      $startTime = Carbon::today()->format('Y-m-d');
      $endTime = Carbon::today()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_WEEKLY) {
      $startTime = $tmpCarbon->startOfWeek()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfWeek()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_MONTHLY) {
      $startTime = $tmpCarbon->startOfMonth()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfMonth()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_QUARTERLY) {
      $startTime = $tmpCarbon->startOfQuarter()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfQuarter()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_HALF_YEARLY) {
      $startTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 7 : 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 12 : 6, 31);
    } else if ($range == Dashboard::RANGE_ANNUALY) {
      $startTime = Carbon::createFromDate(date('Y'), 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), 12, 31);
    }

    $query = $query->with(['items'])->where(['between', 'date', $startTime, $endTime]);

    if ($isNew) $query = $query->distinct();

    $resultToday = collect($query->all())->pluck('ordered_by')->count();
    $resultYesterday = self::getCustomersComparator($range);
    $sum = $resultToday + $resultYesterday;

    return ['comparator' => (($resultToday/$sum) * 100) - (($resultYesterday/$sum)*100), 'value' => $resultToday];
  }

  private static function getSalesReport($range)
  {
    $query = Order::find();
    $startTime = null;
    $endTime = null;
    $tmpCarbon = Carbon::now();

    if ($range == Dashboard::RANGE_DAILY) {
      $startTime = Carbon::today()->format('Y-m-d');
      $endTime = Carbon::today()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_WEEKLY) {
      $startTime = $tmpCarbon->startOfWeek()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfWeek()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_MONTHLY) {
      $startTime = $tmpCarbon->startOfMonth()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfMonth()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_QUARTERLY) {
      $startTime = $tmpCarbon->startOfQuarter()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfQuarter()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_HALF_YEARLY) {
      $startTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 7 : 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 12 : 6, 31);
    } else if ($range == Dashboard::RANGE_ANNUALY) {
      $startTime = Carbon::createFromDate(date('Y'), 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), 12, 31);
    }

    $query = $query->with(['items'])->where(['between', 'date', $startTime, $endTime]);

    $result = $query->all();
    $resultToday = collect($result)->map(function ($order) {
      return $order->items;
    })->flatten(1)->map(function ($order) {
      return $order->qty * $order->price;
    })->reduce(function ($initial, $total) {
      $initial += $total;
      return $initial;
    }, 0);

    $resultYesterday = self::getSalesReportComparator($range);
    $combinedSales = $resultToday + $resultYesterday;

    return ['comparator' => (($resultToday / $combinedSales) * 100) - (($resultYesterday / $combinedSales) * 100), 'value' => $resultToday];
  }

  public static function getUnpaidOrders()
  {
    $orders = Order::find()->where(['is_paid' => 0, 'date' => date('Y-m-d'), 'is_ignored' => 0])->orderBy('order_code asc')->select(['id', 'order_code', 'ordered_by', 'table_number'])->all();

    $tmp = collect([]);

    for ($i = 0; $i < 10; $i++) {
      $tmp = $tmp->merge($orders);
    }

    return $tmp;
  }

  private static function getSellItems($range)
  {
    $query = Order::find();
    $startTime = null;
    $endTime = null;
    $tmpCarbon = Carbon::now();

    if ($range == Dashboard::RANGE_DAILY) {
      $startTime = Carbon::today()->format('Y-m-d');
      $endTime = Carbon::today()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_WEEKLY) {
      $startTime = $tmpCarbon->startOfWeek()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfWeek()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_MONTHLY) {
      $startTime = $tmpCarbon->startOfMonth()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfMonth()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_QUARTERLY) {
      $startTime = $tmpCarbon->startOfQuarter()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfQuarter()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_HALF_YEARLY) {
      $startTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 7 : 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 12 : 6, 31);
    } else if ($range == Dashboard::RANGE_ANNUALY) {
      $startTime = Carbon::createFromDate(date('Y'), 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), 12, 31);
    }

    $query = $query->with(['items'])->where(['between', 'date', $startTime, $endTime]);

    $result = $query->all();
    $result = collect($result)->map(function ($order) {
      return $order->items;
    })->flatten(1)
      ->groupBy('name')
      ->map(function ($group) {
        return $group->pluck('qty')->reduce(function ($init, $next) {
          $init += $next;
          return $init;
        }, 0);
      });

    return $result;
  }

  private static function getSalesReportComparator($range)
  {
    $query = Order::find();
    $startTime = null;
    $endTime = null;
    $tmpCarbon = Carbon::now();

    if ($range == Dashboard::RANGE_DAILY) {
      $startTime = Carbon::today()->subDay()->format('Y-m-d');
      $endTime = Carbon::today()->subDay()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_WEEKLY) {
      $startTime = $tmpCarbon->startOfWeek()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfWeek()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_MONTHLY) {
      $startTime = $tmpCarbon->startOfMonth()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfMonth()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_QUARTERLY) {
      $startTime = $tmpCarbon->startOfQuarter()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfQuarter()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_HALF_YEARLY) {
      $startTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 7 : 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 12 : 6, 31);
    } else if ($range == Dashboard::RANGE_ANNUALY) {
      $startTime = Carbon::createFromDate(date('Y'), 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), 12, 31);
    }

    $query = $query->with(['items'])->where(['between', 'date', $startTime, $endTime]);

    $result = $query->all();
    $result = collect($result)->map(function ($order) {
      return $order->items;
    })->flatten(1)->map(function ($order) {
      return $order->qty * $order->price;
    })->reduce(function ($initial, $total) {
      $initial += $total;
      return $initial;
    }, 0);

    return $result;
  }

  private static function getCustomersComparator($range, $isNew = false)
  {
    $query = Order::find()->select('ordered_by');
    $startTime = null;
    $endTime = null;
    $tmpCarbon = Carbon::now();

    if ($range == Dashboard::RANGE_DAILY) {
      $startTime = Carbon::today()->subDay()->format('Y-m-d');
      $endTime = Carbon::today()->subDay()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_WEEKLY) {
      $startTime = $tmpCarbon->startOfWeek()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfWeek()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_MONTHLY) {
      $startTime = $tmpCarbon->startOfMonth()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfMonth()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_QUARTERLY) {
      $startTime = $tmpCarbon->startOfQuarter()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfQuarter()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_HALF_YEARLY) {
      $startTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 7 : 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 12 : 6, 31);
    } else if ($range == Dashboard::RANGE_ANNUALY) {
      $startTime = Carbon::createFromDate(date('Y'), 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), 12, 31);
    }

    $query = $query->with(['items'])->where(['between', 'date', $startTime, $endTime]);

    if ($isNew) $query = $query->distinct();

    $result = collect($query->all())->pluck('ordered_by')->count();

    return $result;
  }

  private static function getTotalOrdersComparator($range)
  {
    $query = Order::find();
    $startTime = null;
    $endTime = null;
    $tmpCarbon = Carbon::now();

    if ($range == Dashboard::RANGE_DAILY) {
      $startTime = Carbon::today()->subDay()->format('Y-m-d');
      $endTime = Carbon::today()->subDay()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_WEEKLY) {
      $startTime = $tmpCarbon->startOfWeek()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfWeek()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_MONTHLY) {
      $startTime = $tmpCarbon->startOfMonth()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfMonth()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_QUARTERLY) {
      $startTime = $tmpCarbon->startOfQuarter()->format('Y-m-d');
      $endTime = $tmpCarbon->endOfQuarter()->format('Y-m-d');
    } else if ($range == Dashboard::RANGE_HALF_YEARLY) {
      $startTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 7 : 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), intval(date('m')) > 6 ? 12 : 6, 31);
    } else if ($range == Dashboard::RANGE_ANNUALY) {
      $startTime = Carbon::createFromDate(date('Y'), 1, 1);
      $endTime = Carbon::createFromDate(date('Y'), 12, 31);
    }

    $query = $query->with(['items'])->where(['between', 'date', $startTime, $endTime]);

    $result = collect($query->all())->count();

    return $result;
  }
}
