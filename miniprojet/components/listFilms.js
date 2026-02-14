import { useDispatch, useSelector } from "react-redux";
import { Film } from "./film.js";

//import { useSelector } from "react-redux";
export function ListeFilms(){
const liste=useSelector(st=>st.films.listeFilms);

return (
   <div className="d-flex flex-wrap w-100 justify-content-between">

 { liste.map((film)=>
 <div 
 key={film.Id}
 className="card my-3">
<Film film={film}/>
</div>
)}
 
   </div>
)
}