import { useSelector,useDispatch } from "react-redux";
import { Film } from "./film.js";

import { Link } from "react-router-dom";
import { Supprimer } from "../store/sliceFilms.js";

export function SupprimerFilm(){
const liste=useSelector(st=>st.films.listeFilms);
const dispatch=useDispatch();

return (
    <>
     <h3>
        Supprimer un Film
     </h3>
   <div className="d-flex flex-wrap w-100 justify-content-between">
    
 {liste.map((film)=>
 <div 
 key={film.Id}
 className="card my-3">
<Film film={film}/>

  <div className="footer py-2 px-4 ">
    <button 
    onClick={()=>
    {
        if(window.confirm("Vous voulezz Supprimer ce Film ?")){
           dispatch(Supprimer(film.Id)) 
        }
    }
    }
    className="btn btn-danger mx-3">
        Supprimer
    </button>
    <Link 
    to="/"
    className="btn btn-secondary mx-3">
        Retour
    </Link>
    </div>  </div>)}
   </div></>
)
}