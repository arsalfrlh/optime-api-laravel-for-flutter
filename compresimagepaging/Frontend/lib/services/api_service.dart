import 'package:dio/dio.dart';
import 'package:toko/models/barang.dart';
import 'package:toko/models/video.dart';

class ApiService {
  final dio = Dio(BaseOptions(
    baseUrl: "http://192.168.0.105:8000/api",
    sendTimeout: Duration(seconds: 20),
    receiveTimeout: Duration(seconds: 20)
  ));
  
  Future<Map<String, dynamic>> getAllBarang(int page)async{
    try{
      final response = await dio.get("/barang?page=$page");
      return response.data;
    }on DioException catch(e){
      throw Exception(e.response);
    }
  }

  Future<Map<String, dynamic>> addBarang(Barang barang)async{
    List<MultipartFile> gambarPath = [];
    try{
      for(var gambar in barang.gambar){
        gambarPath.add(await MultipartFile.fromFile(gambar.namaGambar));
      }
      
      final request = FormData.fromMap({
        "nama_barang": barang.namaBarang,
        "merk": barang.merk,
        "stok": barang.stok,
        "harga": barang.harga,
        "gambar[]": gambarPath
      });
      
      final response = await dio.post("/barang", data: request);
      return response.data;
    }on DioException catch(e){
      return{
        "success": false,
        "message": e.response.toString()
      };
    }
  }

  Future<Barang> showBarang(int barangId)async{
    try{
      final response = await dio.get("/barang/$barangId");
      return Barang.fromJson(response.data['data']);
    }on DioException catch(e){
      throw Exception(e.response);
    }
  }

  Future<List<Video>> getAllVideo()async{
    try{
      final response = await dio.get("/video");
      return(response.data['data'] as List).map((item) => Video.fromJson(item)).toList();
    }on DioException catch(e){
      throw Exception(e.response);
    }
  }
}