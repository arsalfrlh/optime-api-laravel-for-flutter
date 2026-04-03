import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:toko/viewmodels/barang_viewmodel.dart';

class ShowBarangView extends StatefulWidget {
  const ShowBarangView({required this.barangId, required this.onClose});
  final int barangId;
  final VoidCallback onClose;

  @override
  State<ShowBarangView> createState() => _ShowBarangViewState();
}

class _ShowBarangViewState extends State<ShowBarangView> {
  @override
  void initState() {
    super.initState();
    //gunakan tanpa Future.Microtask, atau WidgetBinding.instance.post agar simple dan otomatis scroll ke atas di ListView, saran kedua gunakan videmodel terpisah
    final barangVM = Provider.of<BarangViewmodel>(context, listen: false);
    barangVM.showBarang(widget.barangId);
  }

  @override
  Widget build(BuildContext context) {
    final barangVM = Provider.of<BarangViewmodel>(context);
    return FractionallySizedBox(
      heightFactor: 0.95,
      child: Scaffold(
        backgroundColor: Colors.white,
        body: SafeArea(
          child: Padding(
            padding: EdgeInsetsGeometry.only(
              bottom: MediaQuery.of(context).viewInsets.bottom
            ),
            child: barangVM.isLoading
            ? Center(child: CircularProgressIndicator(),)
            : Column(
              children: [
                IconButton(onPressed: widget.onClose, icon: Icon(Icons.close)),
                Text("Nama Barang: ${barangVM.currentBarang?.namaBarang}"),
                Text("Merk: ${barangVM.currentBarang?.merk}"),
                Text("Stok: ${barangVM.currentBarang?.stok}"),
                Text("harga: ${barangVM.currentBarang?.harga}"),
                CachedNetworkImage(imageUrl: "http://10.0.2.2:8000/storage/${barangVM.currentBarang?.gambar[0].namaGambar}", fit: BoxFit.cover, width: 80, height: 80,)
              ],
            ),
          ),
        ),
      ),
    );
  }
}