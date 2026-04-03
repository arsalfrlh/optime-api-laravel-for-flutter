import 'package:toko/models/gambar.dart';

class Barang {
  final int id;
  final String namaBarang;
  final String merk;
  final int stok;
  final int harga;
  final List<Gambar> gambar;

  Barang({required this.id, required this.namaBarang, required this.merk, required this.stok, required this.harga, required this.gambar});
  factory Barang.fromJson(Map<String, dynamic> json){
    return Barang(
      id: json['id'],
      namaBarang: json['nama_barang'],
      merk: json['merk'],
      stok: json['stok'],
      harga: json['harga'],
      gambar: (json['gambar'] as List).map((item) => Gambar.fromJson(item)).toList()
    );
  }
}