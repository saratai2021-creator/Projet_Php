import { Link } from "react-router-dom";

export function Film({film}){
return (<div className="card-body text-center">
       <Link 
       to={`/ajout_mody/${film.Id}`}
       className="btn btn-light">
        <img
        alt=""
        src={film.Poster}
        width="250px"
        ></img>
       </Link>
        <p className="card-text">
            <strong>
                Titre:
            </strong>
            {film.Titre}
        </p>
         <p className="card-text">
            <strong>
                Réalisateur:
            </strong>
            {film.Réalisateur}
        </p>
          <p className="card-text">
            <strong>Durée:
                </strong> 
                {film.Durée} min
                </p>
    </div>)
   

}