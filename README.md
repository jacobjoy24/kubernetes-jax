Official Documentation: https://kubernetes.io/docs/home/

Why ? \
	\
        Service discovery and load balancing\
	Storage Orchestration\
	Automated rollouts and rollbacks\
	Automatic bin packing\
	Self healing\
	Secret and configuration Management


Create the **K8cluster**

``` shell
kubectl get nodes
kubectl config view

# Update the config

cat .kube/config
KUBECONFIG=./sample.yaml

kubectl get nodes
```



**Pods** are the smallest deployable units of computing that you can create and manage in Kubernetes. A Pod is a group of one or more *containers* (Is a standard unit of software that packages up code and all its dependencies), with shared storage and network resources, and a specification for how to run the containers.

``` Shell
kubectl run --image ghcr.io/trion-development/echoserver:1 demo
kubectl get pods

# Start a bad pods

kubectl run --image ghcr.io/trion-development/echoserver:bad badpod
kubectl get pods #check the status
kubectl describe pod badpod

# Change image of a container in a Pod

kubectl set image pod badpod badpod=ghcr.io/trion/echoserver:2

# Observe that the container starts and Pod status changes to running

kubectl get pods

# Logs

kubectl logs demo

# Restart Policy
kubectl run clock --image ghcr.io/trion/e:1 --restart=Never date

# Open a shell inside a pod
kubectl exec -it demo -- /bin/sh

# Kill the pod and see that pod restart automatically
ls -l /proc/*/exe
kill -s TERM 1
kubectl get pods

# Determine IP of the target pod
kubectl get pods -o wide
curl http://IP:3000

# Access pods from another pod

kubectl get pods -o wide
kubectl run shell --rm -it --image=trion 
(Starting a Pod with --rm and -it it is deleted after shell closing)
curl http://pod-ip:3000

# Deleting all pods
kubectl delete pods -all

# Output pod config in yaml or json file
kubectl get pods -o yaml
kubectl run demo --image ghcr.io/trion-development/echoserver:1 --env foo=bar --dry-run=client -o yaml > pod.yaml
cat pod.yaml
kubectl create -f pod.yaml
```

**Sample pod yaml file**

```YAML

apiVersion: v1
kind: Pod
metadata:
  labels:
    run: demo
  name: demo
spec:
  containers:
  - env:
    - name: foo
      value: bar
    image: ghcr.io/trion-development/echoserver:1
    name: demo
    ports:
    - containerPort: 3000
    resources:
      requests:
        cpu: 50m
        memory: 64Mi
      limits:
        cpu: 100m
        memory: 64Mi

```

```Shell
# Apply the changes

kubectl apply -f pod.yaml
kubectl replace -f pod.yaml
kubectl apply --force -f pod.yaml

# Watch and changes and events in one terminal

kubectl get pods --watch
kubectl get events --watch

# Enable metrics

minikube addons enable metrics-server
kubectl top nodes
kubectl top pods
```

**Namespace** - isolating groups of resources within a single cluster

```Shell
# Delete all pods in default namespace
kubectl delete pods --all -n default

# Create namespace
kubectl create namespace workshop

# Configure "workshop" namespace as default
kubectl config set-context --current --namespace=workshop


```

Run **Container** inside a pod

``` Dockerfile
FROM php:8-apache
COPY index.php /var/www/html
```

```PHP
# index.php

<p>Date: <?= date('c') ?></p>
<p>Host: <?= gethostname() ?></p>
<p>id: <?= uniqid() ?></p>

```

```YAML
apiVersion: v1
kind: Pod
metadata:
  labels:
    run: demo
  name: php-app
spec:
  containers:
  - name: app
    image: ttl.sh/${IMAGE_NAME}:8h
    ports:
    - containerPort: 80
    resources:
      requests:
        cpu: 50m
        memory: 64Mi
      limits:
        cpu: 100m
        memory: 64Mi
```
```Dockerfile

IMAGE_NAME=$(uuidgen)
docker build -t ttl.sh/${IMAGE_NAME}:8h .
docker push ttl.sh/${IMAGE_NAME}:8h

# Run the pod with the pushed image
kubectl run php-app --image ttl.sh/${IMAGE_NAME}:8h --port 80
```

**Labels** are key/value pairs that are attached to [objects](https://kubernetes.io/docs/concepts/overview/working-with-objects/#kubernetes-objects) such as Pods.

```
kubectl get pods --show-labels
```

**Service** discovery for exposing the application, you can use following type
		type: ClusterIP
		type: NodePort
	    type: LoadBalancer
		type: ExternalName (DNS)
	
```YAML
apiVersion: v1
kind: Service
metadata:
  labels:
    run: demo
  name: php-app
spec:
  ports:
  - port: 80
    protocol: TCP
    targetPort: 80
  selector:
    run: demo
```

```
kubectl expose pod php-app
kubectl apply -f service.yml
```


**Scheduling** - refers to make sure that Pods are matched to Nodes 
			   kublet can run them\
**etcd** - etcd database stores cluster state\
**ReplicaSet** - Is to maintain a stable set of replica pods running at any given time\
**Ingress** - Exposes HTTP and HTTPS routes from outside the cluster to the service\
**Kublet** - The primary node agent that runs on each node, agent to report and maintain node health
 

Additional Info

Configuration Management - https://kustomize.io
 \
Package Manger - https://helm.sh \
Live Environment - https://killercoda.com \
Jsonnet - https://jsonnet.org \
Docker Swarm - Orchestration of the docker container \
Jib Build without using Docker Dameon \
Temp Docker Registry - https://ttl.sh \
Git Registry - https://about.gitea.com \
Docker CI/CD - Woodpecker CI or Drone \
Continuous Delivery Tool - https://argo-cd.readthedocs.io/en/stable/ \
Local development for K8s - https://www.telepresence.io https://gefyra.dev

  
