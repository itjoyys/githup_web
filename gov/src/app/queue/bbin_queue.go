package queue

//bbin队列
type BbinPramar struct {
	siteid, date, starttime, endtime, typeid, gametype, subgamekind string
	page     ,queueid                                                       int
	ismanual                                                        bool
}

type BbinQueue struct {
	PoolSize int
	PoolChan chan BbinPramar
}

func NewQueue(size int) *BbinQueue {
	return &BbinQueue{
		PoolSize: size,
		PoolChan: make(chan BbinPramar, size),
	}
}

func (this *BbinQueue) Init(size int) *BbinQueue {
	this.PoolSize = size
	this.PoolChan = make(chan BbinPramar, size)
	return this
}

func (this *BbinQueue) Push(i BbinPramar) bool {
	if len(this.PoolChan) == this.PoolSize {
		return false
	}
	this.PoolChan <- i
	return true
}

func (this *BbinQueue) PushSlice(s []BbinPramar) {
	for _, i := range s {
		this.Push(i)
	}
}

func (this *BbinQueue) Pull() BbinPramar {
	if len(this.PoolChan) == 0 {
		return BbinPramar{}
	}

	return <-this.PoolChan
}

// 二次使用Queue实例时，根据容量需求进行高效转换
func (this *BbinQueue) Exchange(num int) (add int) {
	last := len(this.PoolChan)

	if last >= num {
		add = int(0)
		return
	}

	if this.PoolSize < num {
		pool := []BbinPramar{}
		for i := 0; i < last; i++ {
			pool = append(pool, <-this.PoolChan)
		}
		// 重新定义、赋值
		this.Init(num).PushSlice(pool)
	}

	add = num - last
	return
}
