class Video {
  final int id;
  final String title;
  final String videoUrl;
  final String thumbnail;
  final int duration;
  final DateTime? createAt;

  Video({required this.id, required this.title, required this.videoUrl, required this.thumbnail, required this.duration, this.createAt});
  factory Video.fromJson(Map<String, dynamic> json){
    return Video(
      id: json['id'],
      title: json['title'],
      videoUrl: json['video_path'],
      thumbnail: json['thumbnail_path'],
      duration: json['duration'],
      createAt: json['created_at'] != null ? DateTime.parse(json['created_at']) : null
    );
  }
}