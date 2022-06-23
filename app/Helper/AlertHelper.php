<?php

namespace App\Helper;

use RealRashid\SweetAlert\Facades\Alert;

class AlertHelper
{

    public static function addAlert($info)
    {
        if ($info) {
            return Alert::success('Berhasil', "Berhasil disimpan");
        } else {
            return Alert::error('Gagal', "Gagal disimpan");
        }
    }

    public static function updateAlert($info)
    {
        if ($info) {
            Alert::success('Berhasil', "Berhasil diubah");
        } else {
            Alert::error('Gagal', 'Gagal diubah');
        }
    }

    public static function deleteAlert($info)
    {
        if ($info) {
            Alert::success('Berhasil', "Berhasil dihapus");
        } else {
            Alert::error('Gagal', 'Gagal dihapus');
        }
    }

    public static function addStock($info)
    {
        if ($info) {
            Alert::success('Berhasil', 'Berhasil disimpan');
        } else {
            Alert::error('Gagal', 'Qty berlebihan');
        }
    }
}
