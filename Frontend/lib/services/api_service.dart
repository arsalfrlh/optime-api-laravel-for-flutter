import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:toko/models/barang.dart';

class ApiService {
  final String baseUrl = "http://10.0.2.2:8000/api";

  Future<Map<String, dynamic>> getBarangPaginate(int? paginate)async{
    try{
      final response = await http.get(Uri.parse("$baseUrl/barang/paginate?page=$paginate"));
      if(response.statusCode == 200){
        return json.decode(response.body)['data'];
      }else{
        throw Exception(json.decode(response.body));
      }
    }catch(e){
      throw Exception(e);
    }
  }

  Future<Map<String, dynamic>> addBarangPaginate(Barang barang, XFile gambar)async{
    try{
      final request = http.MultipartRequest("POST", Uri.parse("$baseUrl/barang/paginate/create"));
      request.fields['nama_barang'] = barang.namaBarang;
      request.fields['merk'] = barang.namaBarang;
      request.fields['stok'] = barang.stok.toString();
      request.fields['harga'] = barang.harga.toString();
      request.files.add(await http.MultipartFile.fromPath("gambar", gambar.path));

      final response = await request.send();
      final responseData = await http.Response.fromStream(response);
      if(responseData.statusCode == 200){
        return json.decode(responseData.body);
      }else{
        return{
          "success": false,
          'message': json.decode(responseData.body)
        };
      }
    }catch(e){
      return{
        "success": false,
        "message": e
      };
    }
  }

  Future<List<Barang>> getBarangCache()async{
    try{
      final response = await http.get(Uri.parse("$baseUrl/barang/cache"));
      if(response.statusCode == 200){
        final List<dynamic> data = json.decode(response.body)['data'];
        return data.map((item) => Barang.fromJson(item)).toList();
      }else{
        throw Exception(json.decode(response.body));
      }
    }catch(e){
      throw Exception(e);
    }
  }

  Future<Map<String, dynamic>> addBarangCache(Barang barang, XFile gambar)async{
    try{
      final request = http.MultipartRequest("POST", Uri.parse("$baseUrl/barang/cache/create"));
      request.fields['nama_barang'] = barang.namaBarang;
      request.fields['merk'] = barang.merk;
      request.fields['stok'] = barang.stok.toString();
      request.fields['harga'] = barang.harga.toString();
      request.files.add(await http.MultipartFile.fromPath("gambar", gambar.path));

      final response = await request.send();
      final responseData = await http.Response.fromStream(response);
      if(responseData.statusCode == 200){
        return json.decode(responseData.body);
      }else{
        return{
          "success": false,
          "message": json.decode(responseData.body)
        };
      }
    }catch(e){
      return{
        "success": false,
        "message": e
      };
    }
  }

  Future<Map<String, dynamic>> updateBarang(Barang barang, XFile? gambar)async{
    try{
      final request = http.MultipartRequest("POST", Uri.parse("$baseUrl/barang/job/update"));
      request.fields['id'] = barang.id.toString();
      request.fields['nama_barang'] = barang.namaBarang;
      request.fields['merk'] = barang.merk;
      request.fields['stok'] = barang.stok.toString();
      request.fields['harga'] = barang.harga.toString();
      if(gambar != null){
        request.files.add(await http.MultipartFile.fromPath("gambar", gambar.path));
      }

      final response = await request.send();
      final responseData = await http.Response.fromStream(response);
      if(responseData.statusCode == 200){
        return json.decode(responseData.body);
      }else{
        return{
          "success": false,
          'message': json.decode(responseData.body)
        };
      }
    }catch(e){
      return{
        "success": false,
        "message": e
      };
    }
  }

  Future<void> deleteBarang(int id)async{
    try{
      await http.delete(Uri.parse("$baseUrl/barang/hapus/$id"));
    }catch(e){
      throw Exception(e);
    }
  }

  Future<Map<String, dynamic>> searchBarang(String search, int? page)async{
    try{
      final response = await http.get(Uri.parse("$baseUrl/barang/paginate/search?search=$search&page=$page"));
      if(response.statusCode == 200){
        return json.decode(response.body)['data'];
      }else{
        throw Exception(json.decode(response.body));
      }
    }catch(e){
      throw Exception(e);
    }
  }
}
