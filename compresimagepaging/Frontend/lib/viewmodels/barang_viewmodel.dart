import 'package:flutter/material.dart';
import 'package:toko/models/barang.dart';
import 'package:toko/models/gambar.dart';
import 'package:toko/services/api_service.dart';

class BarangViewmodel extends ChangeNotifier {
  final _apiService = ApiService();
  List<Barang> barangList = [];
  bool isLoading = false;
  bool isFetchMore = false;
  bool isAction = false;
  String? message;
  Barang? currentBarang;
  int lastPage = 1;
  int currentPage = 1;

  Future<void> fetchBarang()async{
    isLoading = true;
    notifyListeners();
    final response = await _apiService.getAllBarang(1);
    barangList = (response['data']['barang'] as List).map((item) => Barang.fromJson(item)).toList();
    lastPage = response['data']['pagination']['last_page'];
    currentPage = response['data']['pagination']['current_page'];
    isLoading = false;
    notifyListeners();
  }

  Future<bool> addBarang(String namaBarang, String merk, int stok, int harga, List<String> gambarList)async{
    isAction = true;
    message = null;
    notifyListeners();
    final gambarPath = gambarList.map((gambar) => Gambar(id: 0, namaGambar: gambar)).toList();
    final newBarang = Barang(
      id: 0,
      namaBarang: namaBarang,
      merk: merk,
      stok: stok,
      harga: harga,
      gambar: gambarPath
    );
    final response = await _apiService.addBarang(newBarang);
    if(response['success'] == true){
      barangList.add(Barang.fromJson(response['data']));
    }
    isAction = false;
    message = response['message'];
    notifyListeners();
    return(response['success'] as bool);
  }

  Future<void> showBarang(int barangId)async{
    isLoading = true;
    currentBarang = null;
    notifyListeners();
    isLoading = false;
    currentBarang = await _apiService.showBarang(barangId);
    notifyListeners();
  }
  
  Future<void> fetchNextScroll()async{
    if(currentPage >= lastPage || isFetchMore) return;
    isFetchMore = true;
    notifyListeners();
    final response = await _apiService.getAllBarang(currentPage + 1);
    currentPage = response['data']['pagination']['current_page'];
    lastPage = response['data']['pagination']['last_page'];
    barangList.addAll((response['data']['barang'] as List).map((item) => Barang.fromJson(item)).toList());
    isFetchMore = false;
    notifyListeners();
  }
}