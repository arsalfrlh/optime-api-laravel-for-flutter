import 'package:flutter/material.dart';
import 'package:toko/pages/cache/barang_cache_page.dart';
import 'package:toko/pages/paginator/barang_paginator_page.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: "Optimise API",
      home: BarangPaginatorPage(),
    );
  }
}