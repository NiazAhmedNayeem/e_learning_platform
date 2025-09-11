<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Skills</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h4>Add Skills</h4>

    <form action="{{ route('skill.store') }}" method="POST">
        @csrf
       <div id="skill-row">
            <div id="skill-input">
                <input type="text" name="skill[]" placeholder="Enter a skill">
                <button type="button" id="add-skill" class="btn btn-success">+</button>
            </div>
       </div>
       <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </div>



  <div class="container mt-4">
    <h4>Update Skills</h4>

    <form action="{{ route('skill.update') }}" method="POST">
        @csrf
       <div id="edit-skill-row">
        @foreach ($skills as $k => $skill)
            <div id="edit-skill-input">
                <input type="text" name="skill[]" value="{{ $skill->skill }}" placeholder="Enter a skill">
                @if ($k == 0)
                    <button type="button" id="edit-add-skill" class="btn btn-success">+</button>
                @else
                    <button type="button" id="edit-remove-skill" class="btn btn-danger">-</button>
                @endif
            </div>
        @endforeach
            
       </div>
       <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function(){
       
        $(document).on('click', '#add-skill', function(){
            let newInput = `<div id="skill-input">
                                <input type="text" name="skill[]" placeholder="Enter a skill">
                                <button type="button" id="remove-skill" class="btn btn-danger">-</button>
                            </div>`
            $('#skill-row').append(newInput);
        });

        $(document).on('click', '#remove-skill', function(){
            $(this).closest('#skill-input').remove();
        });


        $(document).on('click', '#edit-add-skill', function(){
            let newInput = `<div id="skill-input">
                                <input type="text" name="skill[]" placeholder="Enter a skill">
                                <button type="button" id="edit-remove-skill" class="btn btn-danger">-</button>
                            </div>`
            $('#edit-skill-row').append(newInput);
        });

        $(document).on('click', '#edit-remove-skill', function(){
            $(this).closest('#edit-skill-input').remove();
        });

    });
  </script>
</body>
</html>
