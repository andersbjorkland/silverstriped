<!DOCTYPE html>
<html lang="$ContentLocale">
<head>
    <% base_tag %>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
</head>
<body>
    <main class="container">
        <h1>Book Results</h1>
        <div class="container">

        <% if $books %>
        <% loop $books %>
            
            <% if $Modulus(3) == 1 %>
            <div class="row">
            <% end_if %>

            <div class="col-12 col-sm-6 col-md-4 p-4">
                <div class="card h-100">
                    <img src="$image" alt="" class="card-img">
                    <div class="card-body shadow d-flex flex-column card-img-overlay h-50 p-4 mt-auto bg-primary bg-gradient text-white rounded">
                        <div class="card-title">
                            <h4>$title</h4>
                        </div>
                        <div class="flex">
                            <% loop $authors %>
                            <p>$AuthorName</p>
                            <% end_loop %>
                        </div>
                        <button 
                            class="btn btn-light mt-auto mx-auto" 
                            data-bs-toggle="modal" 
                            data-bs-target="#bookModal-$Pos"    
                        >Description</button>
                    </div>
                </div>
            </div>
            <div id="bookModal-$Pos" class="modal fade" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 id="bookModalLabel" class="modal-title">$title</h5>
                            <button 
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>
                        <div class="modal-body">
                        <p>$description</p>
                        </div>
                        <div class="modal-footer">
                            <button 
                                class="btn btn-secondary" 
                                type="button"
                                data-bs-dismiss="modal"
                            >Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <% if $Modulus(3) == 0 %>
            </div>   
            <% end_if %>

        <% end_loop %>
        <% loop $pagination %>
        <div class="container gap-4 p-4">
            <div class="row justify-content-center">
                <% if $start.link %>
                <a href="$start.link" class="col-1 btn btn-sm btn-secondary">|<</a>                        
                <% end_if %>
                <% if $previous.link %>
                <a href="$previous.link" class="col-1 btn btn-sm btn-secondary"><</a>                        
                <% end_if %>
                <% if $next.link %>
                <a href="$next.link" class="col-1 btn btn-sm btn-secondary">></a>                        
                <% end_if %>
                <% if $start.link %>
                <div class="col-1"></div>
                <% end_if %>
            </div>
        </div>    
        <% end_loop %>
        
        <% end_if %>

        </div>
    </main>
    <% require themedJavascript('dist/bundle') %>
</body>
</html>
