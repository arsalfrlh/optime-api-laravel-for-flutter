import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:toko/viewmodels/video_viewmodel.dart';
import 'package:video_player/video_player.dart';

class VideoView extends StatefulWidget {
  const VideoView({super.key});

  @override
  State<VideoView> createState() => _VideoViewState();
}

class _VideoViewState extends State<VideoView> {
  @override
  void initState() {
    super.initState();
    Future.microtask((){
      final videoVM = Provider.of<VideoViewmodel>(context, listen: false);
      videoVM.fetchVideo();
    });
  }

  @override
  void dispose() {
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final videoVM = Provider.of<VideoViewmodel>(context);
    return Scaffold(
      backgroundColor: Colors.black,
      body: videoVM.isLoading
      ? Center(child: CircularProgressIndicator(),)
      : PageView.builder(
        controller: videoVM.pageController,
        itemCount: videoVM.videoList.length,
        scrollDirection: Axis.vertical,
        onPageChanged: (value) {
          videoVM.playVideo(videoVM.videoList[value]);
        },
        itemBuilder: (context, index) {
          final video = videoVM.videoList[index];
          return Stack(
            alignment: Alignment.center,
            children: [
              videoVM.isInitialize
              ? VideoPlayer(videoVM.controller!)
              : CachedNetworkImage(imageUrl: "http://192.168.0.103:8000/storage/${video.thumbnail}", fit: BoxFit.cover, errorWidget: (context, url, error) => Icon(Icons.broken_image, size: 120,),),
              SizedBox(height: 20,),
              Text(video.title),
              SizedBox(height: 10,),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Slider(
                    min: 0,
                    max: videoVM.duration.inMilliseconds.toDouble(),
                    value: videoVM.position.inMilliseconds.clamp(0, videoVM.duration.inMilliseconds).toDouble(),
                    onChanged: (value) {
                      videoVM.seek(Duration(milliseconds: value.toInt()));
                    },
                  ),
                  SizedBox(width: 10,),
                  IconButton(onPressed: () => videoVM.isPlaying ? videoVM.pause() : videoVM.play(), icon: Icon(videoVM.isPlaying ? Icons.pause : Icons.play_arrow))
                ],
              )
            ],
          );
        },
      ),
    );
  }
}