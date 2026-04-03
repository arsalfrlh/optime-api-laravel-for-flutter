import 'package:flutter/material.dart';
import 'package:toko/models/video.dart';
import 'package:toko/services/api_service.dart';
import 'package:toko/services/video_service.dart';
import 'package:video_player/video_player.dart';

class VideoViewmodel extends ChangeNotifier {
  final _apiService = ApiService();
  final _videoService = VideoService();
  bool isLoading = false;
  String? message;
  List<Video> videoList = [];
  bool isPlaying = false;
  bool isInitialize = false;
  Duration duration = Duration.zero;
  Duration position = Duration.zero;
  final PageController _pageController = PageController();

  VideoPlayerController? get controller => _videoService.controller;
  PageController get pageController => _pageController;

  Future<void> fetchVideo()async{
    isLoading = true;
    notifyListeners();
    videoList = await _apiService.getAllVideo();
    if(videoList.isNotEmpty){
      await playVideo(videoList.first);
    }
    isLoading = false;
    notifyListeners();
  }

  Future<void> playVideo(Video video)async{
    _videoService.controller?.removeListener(listenerVideo);
    _videoService.controller?.dispose();
    isInitialize = false;
    isPlaying = false;
    position = Duration.zero;
    duration = Duration.zero;
    notifyListeners();
    await _videoService.initialize(video.videoUrl);
    _videoService.controller?.addListener(listenerVideo);
    isInitialize = _videoService.controller?.value.isInitialized ?? false;
    duration = _videoService.controller?.value.duration ?? Duration.zero;
    position = _videoService.controller?.value.position ?? Duration.zero;
    isPlaying = _videoService.controller?.value.isPlaying ?? false;
    notifyListeners();
  }

  listenerVideo(){
    isPlaying = _videoService.controller?.value.isPlaying ?? false;
    duration = _videoService.controller?.value.duration ?? Duration.zero;
    position = _videoService.controller?.value.position ?? Duration.zero;
    notifyListeners();
  }

  void play() => _videoService.play();
  void pause() => _videoService.pause();
  Future<void> seek(Duration value) async => _videoService.seek(value);

  @override
  void dispose() {
    _videoService.controller?.removeListener(listenerVideo);
    _videoService.controller?.dispose();
    pageController.dispose();
    super.dispose();
  }
}