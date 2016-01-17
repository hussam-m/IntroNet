/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package BSU.CS690.IntroNet;

import java.util.Properties;

/**
 *
 * @author hussam
 */
public class Request {
    public double id = System.currentTimeMillis();
    public String ip;
    public String type;
    public String path;
    public Properties data;

    @Override
    public String toString() {
        return "Request {id:"+id+",ip:"+ip+",type:"+type+",path:"+path+",data:"+data+"}";
    }
    
    
    
}
