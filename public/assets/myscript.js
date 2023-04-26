$('.delete-user').click( function(){
    var username = $(this).attr('data-username');
    var name = $(this).attr('data-name');
    Swal.fire({
      title: 'Yakin ?',
      text: "Ingin menghapus data user "+name+" ? ",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = "/user-delete/"+username+""
        Swal.fire(
          'Deleted!',
          'User Has Been Remove.',
          'success'
        )
      }
    })
  });
