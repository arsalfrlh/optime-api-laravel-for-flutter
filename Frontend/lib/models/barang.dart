class Barang {
  final int id;
  final String? gambar;
  final String namaBarang;
  final String merk;
  final int stok;
  final int harga;
  
  Barang({required this.id, this.gambar, required this.namaBarang, required this.merk, required this.stok, required this.harga});
  factory Barang.fromJson(Map<String, dynamic> json){
    return Barang(
      id: json['id'],
      gambar: json['gambar'],
      namaBarang: json['nama_barang'],
      merk: json['merk'],
      stok: json['stok'],
      harga: json['harga']
    );
  }
}
