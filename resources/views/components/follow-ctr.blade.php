@props(['user'])
<div {{ $attributes }} x-data="{
    following: {{ $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
    follow() {
        
        axios.post('/follow/{{ $user->id }}')
            .then(res => {
                this.following = !this.following;
                this.followersCount = res.data.followersCount;
            })
            .catch(err => {
                console.log(err)
            })
    },
    followersCount: {{ $user->followers()->count() }},

}" class=" w-[320px] border-l pl-4 border-b-amber-100">
    {{ $slot }}
</div>
